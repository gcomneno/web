# Contributing

[English](CONTRIBUTING.md) | [Italiano](CONTRIBUTING.it.md)

Contributions should keep the repository clear, reproducible, and accessible in both English and Italian.

## Canonical language and file naming

English is the canonical language for public human-authored documentation.

The canonical English document uses the base filename:

- `README.md`
- `CONTRIBUTING.md`
- `lesson-01-learned.md`

The corresponding Italian translation uses the `.it.md` suffix:

- `README.it.md`
- `CONTRIBUTING.it.md`
- `lesson-01-learned.it.md`

When the two versions disagree, the English document defines the intended technical meaning.

## Documentation scope

The bilingual policy applies to public, human-authored documentation intended for readers and contributors.

It includes:

- repository and subproject README files
- contribution policies
- glossaries and summaries
- lessons learned and other educational documentation

It does not normally include:

- dependency or vendor documentation
- generated files
- framework scaffolding kept unchanged from upstream
- fixtures and test data
- complete transcripts, imported material, audio, or video
- machine-oriented files such as `robots.txt`

`laravel-lab/first-project/README.md` is currently treated as framework scaffold documentation and is excluded unless it is substantially rewritten for this repository.

## Paired document requirements

Every migrated bilingual document must have both an English canonical version and an Italian translation.

Each pair must preserve:

- the same heading hierarchy
- equivalent technical meaning
- the same code examples
- equivalent internal navigation
- reciprocal language links near the beginning
- valid relative links in both files

Prose does not need to be translated word for word, but neither version may omit technical requirements, warnings, or operational steps.

## Progressive migration

Existing Italian-only documents may temporarily retain their current unsuffixed filenames while the repository is migrated.

Migration is performed one document pair at a time:

1. rename the existing Italian document with `git mv` to the `.it.md` filename
2. create the canonical English document at the original filename
3. add reciprocal language links
4. align headings, code examples, and navigation
5. register and validate the new pair
6. review the documentation-only diff

New public documentation must not introduce additional Italian-only legacy files.

## Updating documentation

When changing an already migrated document:

1. update the canonical English version
2. apply the same technical change to the Italian version
3. run the bilingual documentation validator
4. inspect the diff for unrelated changes
5. include both files in the same pull request

Code identifiers, commands, paths, filenames, API names, and literal output should normally remain unchanged across translations.

## Review checklist

Before requesting review, verify that:

- [ ] the English canonical document exists
- [ ] the Italian `.it.md` translation exists
- [ ] reciprocal language links work
- [ ] heading levels are synchronized
- [ ] code examples and command sequences match
- [ ] relative links are valid
- [ ] both versions describe the same behavior
- [ ] the diff contains only intended files
- [ ] automated documentation checks pass

## Pull request workflow

Documentation migrations should use a focused branch and a Draft pull request.

Keep each migration small enough to review accurately. Move the pull request to Ready for review only after the bilingual checks pass and the two versions have been compared.

After merge, synchronize the main branch and remove temporary worktrees and branches.
