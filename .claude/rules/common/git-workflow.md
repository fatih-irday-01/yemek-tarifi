---
description: Git commit format and PR process
alwaysApply: false
---

# Git Workflow

## Commit Message Format
```
<type>: <short description>

<optional body — explain WHY, not what>
```

Types: `feat`, `fix`, `refactor`, `docs`, `test`, `chore`, `perf`, `ci`

Examples:
- `feat: add user soft delete with audit log`
- `fix: prevent duplicate email on concurrent registration`
- `test: add regression test for null company_id edge case`

## Pull Request Process
1. Analyze full diff — not just the last commit
2. Write a descriptive PR body: what changed and why
3. Include a test plan checklist
4. Ensure CI/CD passes before requesting review
5. Resolve all conflicts before merge

## Branch Naming
- `feature/<ticket-or-description>`
- `fix/<short-description>`
- `chore/<short-description>`
