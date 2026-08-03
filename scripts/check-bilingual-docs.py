#!/usr/bin/env python3
"""Validate registered English/Italian Markdown document pairs."""

from __future__ import annotations

import json
import re
import sys
from collections import Counter
from dataclasses import dataclass
from pathlib import Path
from urllib.parse import unquote


ROOT = Path(__file__).resolve().parent.parent
MANIFEST_PATH = ROOT / ".github" / "bilingual-docs.json"

HEADING_RE = re.compile(r"^(#{1,6})[ \t]+(.+?)\s*$")
FENCE_RE = re.compile(r"^[ \t]{0,3}(`{3,}|~{3,})(.*)$")
INLINE_CODE_RE = re.compile(r"(?<!`)`([^`\n]+)`(?!`)")
LINK_RE = re.compile(r"!?\[[^\]]*]\(([^)]+)\)")
URI_SCHEME_RE = re.compile(r"^[A-Za-z][A-Za-z0-9+.-]*:")


@dataclass(frozen=True)
class MarkdownDocument:
    path: Path
    text: str
    prose: str
    heading_levels: tuple[int, ...]
    code_blocks: tuple[tuple[str, str], ...]
    inline_code: Counter[str]
    links: tuple[str, ...]


def relative_name(path: Path) -> str:
    return path.relative_to(ROOT).as_posix()


def parse_link_target(raw_target: str) -> str:
    target = raw_target.strip()

    if target.startswith("<") and ">" in target:
        return target[1 : target.index(">")].strip()

    return target.split(maxsplit=1)[0] if target else ""


def parse_markdown(path: Path, errors: list[str]) -> MarkdownDocument:
    text = path.read_text(encoding="utf-8")
    lines = text.splitlines()

    heading_levels: list[int] = []
    code_blocks: list[tuple[str, str]] = []
    prose_lines: list[str] = []

    in_fence = False
    fence_character = ""
    fence_length = 0
    fence_info = ""
    fence_body: list[str] = []

    for line_number, line in enumerate(lines, start=1):
        if not in_fence:
            fence_match = FENCE_RE.match(line)

            if fence_match:
                marker = fence_match.group(1)
                in_fence = True
                fence_character = marker[0]
                fence_length = len(marker)
                fence_info = fence_match.group(2).strip()
                fence_body = []
                continue

            heading_match = HEADING_RE.match(line)

            if heading_match:
                heading_levels.append(len(heading_match.group(1)))

            prose_lines.append(line)
            continue

        stripped = line.lstrip()

        if (
            stripped.startswith(fence_character * fence_length)
            and set(stripped.strip()) <= {fence_character}
            and len(stripped.strip()) >= fence_length
        ):
            code_blocks.append(
                (fence_info, "\n".join(fence_body))
            )
            in_fence = False
            fence_character = ""
            fence_length = 0
            fence_info = ""
            fence_body = []
        else:
            fence_body.append(line)

    if in_fence:
        errors.append(
            f"{relative_name(path)}: blocco di codice non chiuso"
        )

    prose = "\n".join(prose_lines)
    inline_code = Counter(INLINE_CODE_RE.findall(prose))
    links = tuple(
        parse_link_target(raw)
        for raw in LINK_RE.findall(prose)
        if parse_link_target(raw)
    )

    return MarkdownDocument(
        path=path,
        text=text,
        prose=prose,
        heading_levels=tuple(heading_levels),
        code_blocks=tuple(code_blocks),
        inline_code=inline_code,
        links=links,
    )


def translation_for(canonical: str, suffix: str) -> str:
    if not canonical.endswith(".md"):
        raise ValueError(
            f"Il documento canonico non termina con .md: {canonical}"
        )

    if canonical.endswith(suffix):
        raise ValueError(
            f"Il documento canonico usa già il suffisso italiano: {canonical}"
        )

    return canonical[:-3] + suffix


def validate_local_links(
    document: MarkdownDocument,
    errors: list[str],
) -> None:
    for target in document.links:
        if (
            not target
            or target.startswith("#")
            or URI_SCHEME_RE.match(target)
        ):
            continue

        path_part = target.split("#", 1)[0].split("?", 1)[0]

        if not path_part:
            continue

        resolved = document.path.parent / unquote(path_part)

        if not resolved.exists():
            errors.append(
                f"{relative_name(document.path)}: "
                f"link locale inesistente: {target}"
            )


def normalized_link_target(target: str, suffix: str) -> str:
    target = target.strip()

    if not target:
        return target

    if URI_SCHEME_RE.match(target):
        return target

    path_part = target.split("#", 1)[0]
    path_part = path_part.split("?", 1)[0]

    if path_part.endswith(suffix):
        path_part = path_part[: -len(suffix)] + ".md"

    return unquote(path_part)


def validate_language_links(
    canonical: MarkdownDocument,
    translation: MarkdownDocument,
    errors: list[str],
) -> None:
    expected = {
        canonical.path.name,
        translation.path.name,
    }

    for document in (canonical, translation):
        top_lines = "\n".join(document.text.splitlines()[:12])
        top_targets = {
            parse_link_target(raw)
            for raw in LINK_RE.findall(top_lines)
        }

        missing = expected - top_targets

        if missing:
            errors.append(
                f"{relative_name(document.path)}: "
                "link di lingua mancanti nelle prime 12 righe: "
                + ", ".join(sorted(missing))
            )


def validate_pair(
    canonical_path: Path,
    translation_path: Path,
    suffix: str,
    errors: list[str],
) -> None:
    canonical = parse_markdown(canonical_path, errors)
    translation = parse_markdown(translation_path, errors)

    validate_language_links(canonical, translation, errors)
    validate_local_links(canonical, errors)
    validate_local_links(translation, errors)

    if canonical.heading_levels != translation.heading_levels:
        errors.append(
            f"{relative_name(canonical_path)} e "
            f"{relative_name(translation_path)}: "
            "gerarchia degli heading non sincronizzata"
        )

    if canonical.code_blocks != translation.code_blocks:
        errors.append(
            f"{relative_name(canonical_path)} e "
            f"{relative_name(translation_path)}: "
            "blocchi di codice differenti"
        )

    if canonical.inline_code != translation.inline_code:
        missing_in_translation = canonical.inline_code - translation.inline_code
        extra_in_translation = translation.inline_code - canonical.inline_code

        details: list[str] = []

        if missing_in_translation:
            details.append(
                "mancanti in italiano: "
                + ", ".join(sorted(missing_in_translation.elements()))
            )

        if extra_in_translation:
            details.append(
                "aggiuntivi in italiano: "
                + ", ".join(sorted(extra_in_translation.elements()))
            )

        errors.append(
            f"{relative_name(canonical_path)} e "
            f"{relative_name(translation_path)}: "
            "riferimenti tecnici inline non sincronizzati"
            + (f" ({'; '.join(details)})" if details else "")
        )

    canonical_links = Counter(
        normalized_link_target(target, suffix)
        for target in canonical.links
    )
    translation_links = Counter(
        normalized_link_target(target, suffix)
        for target in translation.links
    )

    if canonical_links != translation_links:
        errors.append(
            f"{relative_name(canonical_path)} e "
            f"{relative_name(translation_path)}: "
            "navigazione Markdown non equivalente"
        )


def main() -> int:
    errors: list[str] = []

    try:
        manifest = json.loads(
            MANIFEST_PATH.read_text(encoding="utf-8")
        )
    except FileNotFoundError:
        print(
            f"ERRORE: manifest assente: {relative_name(MANIFEST_PATH)}"
        )
        return 1
    except json.JSONDecodeError as exc:
        print(f"ERRORE: manifest JSON non valido: {exc}")
        return 1

    if not isinstance(manifest, dict):
        print("ERRORE: il manifest JSON deve essere un oggetto")
        return 1

    if manifest.get("schema_version") != 1:
        errors.append("schema_version del manifest non supportata")

    if manifest.get("canonical_language") != "en":
        errors.append("canonical_language deve essere esattamente en")

    if manifest.get("translation_language") != "it":
        errors.append("translation_language deve essere esattamente it")

    suffix = manifest.get("translation_suffix")

    if suffix != ".it.md":
        errors.append(
            "translation_suffix deve essere esattamente .it.md"
        )
        suffix = ".it.md"

    def read_string_list(key: str) -> list[str]:
        value = manifest.get(key)

        if not isinstance(value, list):
            errors.append(f"{key} deve essere una lista")
            return []

        if not all(isinstance(item, str) for item in value):
            errors.append(
                f"{key} deve contenere soltanto stringhe"
            )
            return [
                item for item in value
                if isinstance(item, str)
            ]

        if value != sorted(set(value)):
            errors.append(
                f"{key} deve essere ordinato e senza duplicati"
            )

        return value

    canonical_documents = read_string_list(
        "canonical_documents"
    )
    legacy_documents = read_string_list(
        "legacy_unpaired_documents"
    )
    excluded_documents = read_string_list(
        "excluded_documents"
    )
    ignored_directories = set(
        read_string_list("ignored_directories")
    )

    categories = {
        "canonical_documents": set(canonical_documents),
        "legacy_unpaired_documents": set(legacy_documents),
        "excluded_documents": set(excluded_documents),
    }
    category_names = list(categories)

    for index, left_name in enumerate(category_names):
        for right_name in category_names[index + 1:]:
            overlap = (
                categories[left_name]
                & categories[right_name]
            )

            if overlap:
                errors.append(
                    f"{left_name} e {right_name} "
                    "si sovrappongono: "
                    + ", ".join(sorted(overlap))
                )

    for category_name, documents in (
        (
            "legacy_unpaired_documents",
            legacy_documents,
        ),
        (
            "excluded_documents",
            excluded_documents,
        ),
    ):
        for document_name in documents:
            if not document_name.endswith(".md"):
                errors.append(
                    f"{category_name} contiene un file "
                    f"non Markdown: {document_name}"
                )
                continue

            if (
                category_name
                == "legacy_unpaired_documents"
                and document_name.endswith(suffix)
            ):
                errors.append(
                    f"{category_name} non può contenere "
                    "una traduzione italiana: "
                    f"{document_name}"
                )

            if not (ROOT / document_name).is_file():
                errors.append(
                    f"{category_name} contiene un file "
                    f"assente: {document_name}"
                )

    expected_translations: set[str] = set()

    for canonical_name in canonical_documents:
        if not isinstance(canonical_name, str):
            errors.append(
                "canonical_documents contiene un valore non testuale"
            )
            continue

        try:
            translation_name = translation_for(canonical_name, suffix)
        except ValueError as exc:
            errors.append(str(exc))
            continue

        expected_translations.add(translation_name)

        canonical_path = ROOT / canonical_name
        translation_path = ROOT / translation_name

        if not canonical_path.is_file():
            errors.append(
                f"documento canonico assente: {canonical_name}"
            )

        if not translation_path.is_file():
            errors.append(
                f"traduzione italiana assente: {translation_name}"
            )

        if canonical_path.is_file() and translation_path.is_file():
            validate_pair(
                canonical_path,
                translation_path,
                suffix,
                errors,
            )

    translation_overlap = expected_translations & (
        set(canonical_documents)
        | set(legacy_documents)
        | set(excluded_documents)
    )

    if translation_overlap:
        errors.append(
            "le traduzioni attese si sovrappongono ad altre "
            "categorie del manifest: "
            + ", ".join(sorted(translation_overlap))
        )

    all_markdown: set[str] = set()

    for path in ROOT.rglob("*.md"):
        relative = path.relative_to(ROOT)

        if any(
            part in ignored_directories
            for part in relative.parts
        ):
            continue

        if path.is_file():
            all_markdown.add(relative.as_posix())

    translations_on_disk = {
        name
        for name in all_markdown
        if name.endswith(suffix)
    }

    unregistered_translations = (
        translations_on_disk
        - expected_translations
    )

    if unregistered_translations:
        errors.append(
            "traduzioni italiane non registrate "
            "nel manifest: "
            + ", ".join(
                sorted(unregistered_translations)
            )
        )

    classified_documents = (
        set(canonical_documents)
        | expected_translations
        | set(legacy_documents)
        | set(excluded_documents)
    )

    unclassified_documents = (
        all_markdown
        - classified_documents
        - unregistered_translations
    )

    if unclassified_documents:
        errors.append(
            "documenti Markdown pubblici non classificati "
            "nel manifest: "
            + ", ".join(
                sorted(unclassified_documents)
            )
        )

    if errors:
        print("VALIDAZIONE BILINGUE FALLITA")

        for error in errors:
            print(f"- {error}")

        return 1

    print("VALIDAZIONE BILINGUE SUPERATA")
    print(
        f"Coppie verificate: {len(canonical_documents)}"
    )
    print(
        f"Documenti legacy censiti: {len(legacy_documents)}"
    )
    print(
        f"Documenti esclusi censiti: {len(excluded_documents)}"
    )

    for canonical_name in canonical_documents:
        print(
            f"- {canonical_name} <-> "
            f"{translation_for(canonical_name, suffix)}"
        )

    return 0


if __name__ == "__main__":
    sys.exit(main())
