---
description: Git commit format and PR process
alwaysApply: false
---

<!-- Applies to: all files -->

# Git Workflow

## Commit Message Format
```
<type>: <short description>

<optional body — explain WHY, not what>
```

Types: `feat`, `fix`, `refactor`, `docs`, `test`, `chore`, `perf`, `ci`

## Pull Request Process
1. Analyze full diff — not just the last commit.
2. Descriptive PR body: what changed and why.
3. Include test plan checklist.
4. CI/CD passes before requesting review.
5. Resolve all conflicts before merge.

## Branch Naming
- `feature/<ticket-or-description>`
- `fix/<short-description>`
- `chore/<short-description>`
