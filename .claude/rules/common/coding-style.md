---
description: Universal coding style standards — naming, file size, immutability, error handling
alwaysApply: true
---

# Coding Style

## Core Principles
- **Immutability**: Create new objects instead of mutating existing ones. Never reassign function parameters.
- **KISS / DRY / YAGNI**: Simple over clever. No duplication. No speculative features.
- Functions max 50 lines. Files 200–400 lines typical, 800 hard limit.
- Nesting max 4 levels. Extract early-return guards to reduce nesting.

## Naming
- Functions/variables: `camelCase`
- Classes/types: `PascalCase`
- Constants: `UPPER_SNAKE_CASE`
- Booleans: `is`, `has`, `should`, `can` prefix

## Error Handling
- Handle errors at system boundaries (user input, external APIs).
- UI code: user-friendly messages. Server code: detailed logging.
- Never swallow exceptions silently.

## Quality Checklist (before finishing any task)
- [ ] No magic numbers — use named constants
- [ ] No functions over 50 lines
- [ ] No nesting deeper than 4 levels
- [ ] All inputs validated at boundaries
- [ ] No debug code or commented-out blocks left
