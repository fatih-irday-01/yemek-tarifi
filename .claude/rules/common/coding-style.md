---
description: Universal coding style — naming, size limits, immutability, error handling, efficiency
alwaysApply: true
---

<!-- Applies to: all files -->

# Coding Style

## Core Principles
- **Immutability**: Create new objects; never mutate or reassign function parameters.
- **KISS / DRY / YAGNI**: Simple over clever. No duplication. No speculative features.
- Functions max 50 lines. Files 200–400 lines typical, 800 hard limit.
- Nesting max 4 levels. Use early-return guards.
- No magic numbers — use named constants.
- No debug code or commented-out blocks.

## Naming
- Functions/variables: `camelCase`
- Classes/types: `PascalCase`
- Constants: `UPPER_SNAKE_CASE`
- Booleans: `is`, `has`, `should`, `can` prefix

## Error Handling
- Handle errors at system boundaries only (user input, external APIs).
- UI: user-friendly messages. Server: detailed logs.
- Never swallow exceptions silently.

## Efficiency (Serena Readiness)

When Serena MCP is active, follow this tool hierarchy (most efficient first):

```
1. find_symbol()           → sembol/fonksiyon/sınıf ara
2. get_symbols_overview()  → dosya/dizin outline'ı al
3. read_file(path, range)  → sadece ilgili satır aralığını oku
4. search_for_pattern()    → Serena kapsamlı regex arama
5. grep (Bash)             → son çare — config/doc/YAML dosyaları için
```

- Never `cat` or fully read a file when searching for specific content — use symbol tools first.
- Never read entire files when only a function body is needed — use `read_file` with line range.
- `find_referencing_symbols()` to measure impact before refactoring.
- Bash `grep`/`ls`/`find` only for non-code files (Docker, nginx, YAML, Markdown).

## Execution Mode
- Direct execution. No step-by-step narration. No reasoning preambles.
