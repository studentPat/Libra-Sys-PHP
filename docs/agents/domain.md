# Domain Docs

This repository uses the single-context domain documentation layout.

## Before exploring

- Read `GLOSSARY.md` at the repository root when it exists.
- Read relevant decisions under `docs/adr/` when they exist.
- Proceed without flagging missing domain documents; create them lazily when a domain term or architectural decision needs to be recorded.

## Layout

```text
/
├── GLOSSARY.md
└── docs/adr/
    ├── 0001-example-decision.md
    └── 0002-example-decision.md
```

## Vocabulary

Use terminology from `GLOSSARY.md` when it exists. If a needed domain concept is not defined, treat that as a possible gap for domain modeling rather than silently inventing conflicting terminology.

## Architectural decisions

Surface conflicts with existing ADRs explicitly instead of silently overriding them.
