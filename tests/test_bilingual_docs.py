#!/usr/bin/env python3
"""Regression tests for the bilingual Markdown validator."""

from __future__ import annotations

import importlib.util
import sys
import tempfile
import unittest
from pathlib import Path


ROOT = Path(__file__).resolve().parents[1]
VALIDATOR_PATH = ROOT / "scripts" / "check-bilingual-docs.py"
FENCE_CHR = chr(96)

SPEC = importlib.util.spec_from_file_location(
    "bilingual_docs_validator",
    VALIDATOR_PATH,
)

if SPEC is None or SPEC.loader is None:
    raise RuntimeError(f"Unable to load {VALIDATOR_PATH}")

validator = importlib.util.module_from_spec(SPEC)
sys.modules[SPEC.name] = validator
SPEC.loader.exec_module(validator)


class BilingualValidatorTests(unittest.TestCase):
    def parse_sample(self, content: str):
        with tempfile.TemporaryDirectory(
            prefix=".bilingual-docs-test-",
            dir=ROOT,
        ) as temporary:
            path = Path(temporary) / "sample.md"
            path.write_text(content, encoding="utf-8")

            errors: list[str] = []
            document = validator.parse_markdown(
                path.resolve(),
                errors,
            )

        self.assertEqual(errors, [])
        return document

    def make_pair(
        self,
        directory: Path,
        english_command: str,
        italian_command: str,
    ) -> tuple[Path, Path]:
        canonical = directory / "sample.md"
        translation = directory / "sample.it.md"
        navigation = (
            "[English](sample.md) | "
            "[Italiano](sample.it.md)"
        )

        canonical.write_text(
            (
                "# Sample\n\n"
                f"{navigation}\n\n"
                f"    {english_command}\n"
            ),
            encoding="utf-8",
        )

        translation.write_text(
            (
                "# Esempio\n\n"
                f"{navigation}\n\n"
                f"    {italian_command}\n"
            ),
            encoding="utf-8",
        )

        return canonical, translation

    def test_indented_block_grouping(self) -> None:
        document = self.parse_sample(
            (
                "# Probe\n\n"
                "    command one\n"
                "    command two\n"
                "\n"
                "    command three\n\n"
                "Text.\n"
            )
        )

        self.assertEqual(
            document.code_blocks,
            (
                (
                    "",
                    "command one\n"
                    "command two\n\n"
                    "command three",
                ),
            ),
        )

    def test_indentation_after_paragraph_is_not_code(
        self,
    ) -> None:
        document = self.parse_sample(
            (
                "# Probe\n\n"
                "Paragraph text.\n"
                "    indented continuation\n"
            )
        )

        self.assertEqual(document.code_blocks, ())

    def test_fenced_and_indented_order(self) -> None:
        fence = FENCE_CHR * 3

        document = self.parse_sample(
            (
                "# Probe\n\n"
                f"{fence}bash\n"
                "echo fenced\n"
                f"{fence}\n\n"
                "    echo indented\n"
            )
        )

        self.assertEqual(
            document.code_blocks,
            (
                ("bash", "echo fenced"),
                ("", "echo indented"),
            ),
        )

    def test_pair_accepts_only_equivalent_code(self) -> None:
        with tempfile.TemporaryDirectory(
            prefix=".bilingual-docs-test-",
            dir=ROOT,
        ) as temporary:
            directory = Path(temporary)

            canonical, translation = self.make_pair(
                directory,
                "php artisan route:list",
                "php artisan route:list --path=probe",
            )

            errors: list[str] = []

            validator.validate_pair(
                canonical,
                translation,
                ".it.md",
                errors,
            )

            self.assertTrue(
                any(
                    "blocchi di codice differenti" in error
                    for error in errors
                ),
                errors,
            )

            canonical, translation = self.make_pair(
                directory,
                "php artisan route:list",
                "php artisan route:list",
            )

            errors = []

            validator.validate_pair(
                canonical,
                translation,
                ".it.md",
                errors,
            )

            self.assertEqual(errors, [])

    def test_indented_code_can_follow_heading(
        self,
    ) -> None:
        document = self.parse_sample(
            (
                "# Heading\n"
                "    echo code\n"
            )
        )

        self.assertEqual(
            document.code_blocks,
            (("", "echo code"),),
        )

    def test_list_continuation_is_not_top_level_code(
        self,
    ) -> None:
        document = self.parse_sample(
            (
                "- item\n"
                "\n"
                "    continuation paragraph\n"
            )
        )

        self.assertEqual(document.code_blocks, ())

    def test_indented_code_inside_list_is_detected(
        self,
    ) -> None:
        document = self.parse_sample(
            (
                "- item\n"
                "\n"
                "      echo nested\n"
            )
        )

        self.assertEqual(
            document.code_blocks,
            (("", "echo nested"),),
        )

    def test_multiline_list_continuation_is_not_code(
        self,
    ) -> None:
        document = self.parse_sample(
            (
                "- item\n"
                "  continuation\n"
                "\n"
                "    second paragraph\n"
            )
        )

        self.assertEqual(document.code_blocks, ())

    def test_nested_code_after_list_continuation_is_detected(
        self,
    ) -> None:
        document = self.parse_sample(
            (
                "- item\n"
                "  continuation\n"
                "\n"
                "      echo nested\n"
            )
        )

        self.assertEqual(
            document.code_blocks,
            (("", "echo nested"),),
        )

    def test_two_blank_lines_end_list_before_code(
        self,
    ) -> None:
        document = self.parse_sample(
            (
                "- item\n"
                "\n"
                "\n"
                "    echo top level\n"
            )
        )

        self.assertEqual(
            document.code_blocks,
            (("", "echo top level"),),
        )

    def test_real_laravel_pair(self) -> None:
        errors: list[str] = []

        english = validator.parse_markdown(
            ROOT / "laravel-lab" / "README.md",
            errors,
        )
        italian = validator.parse_markdown(
            ROOT / "laravel-lab" / "README.it.md",
            errors,
        )

        self.assertEqual(errors, [])
        self.assertEqual(len(english.code_blocks), 38)
        self.assertEqual(len(italian.code_blocks), 38)

        english_lines = sum(
            len(body.splitlines())
            for _, body in english.code_blocks
        )
        italian_lines = sum(
            len(body.splitlines())
            for _, body in italian.code_blocks
        )

        self.assertEqual(english_lines, 39)
        self.assertEqual(italian_lines, 39)
        self.assertEqual(
            english.code_blocks,
            italian.code_blocks,
        )


if __name__ == "__main__":
    unittest.main()
