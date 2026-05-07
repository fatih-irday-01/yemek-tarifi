---
description: Geliştirme iş akışı — 5 aşamalı süreç, git commit formatı, PR süreci, code review
alwaysApply: false
---

# Development Workflow

## Five-Phase Process
1. **Research & Reuse** — Check existing patterns. Confirm library API. Prefer proven approaches.
2. **Plan** — Identify affected layers. Define API contract. Architectural decisions → architect agent.
3. **TDD** — Write failing test first. RED → GREEN → REFACTOR.
4. **Code Review** — Security-sensitive changes → security-reviewer. CRITICAL/HIGH: fix before merge.
5. **Commit & Push** — CI/CD must pass. Resolve conflicts before merge.

---

# Git Workflow

## Commit Message
```
<type>: <short description>

<optional body — explain WHY>
```
Types: `feat`, `fix`, `refactor`, `docs`, `test`, `chore`, `perf`, `ci`

## Branch Naming
`feature/<description>` · `fix/<description>` · `chore/<description>`

## Pull Request
1. Analyze full diff — not just last commit.
2. Descriptive body: what changed and why. Include test plan checklist.
3. CI/CD passes. Resolve all conflicts before merge.

---

# Code Review

## When Mandatory
After any non-trivial code block · Before committing to shared branches · Before PR merge · For security-sensitive changes.

## Review Checklist
- [ ] Names are self-explanatory
- [ ] Error handling at boundaries
- [ ] No hardcoded secrets or debug statements
- [ ] Test coverage maintained

## Security Triggers → escalate to security-reviewer
Auth/session · User input · DB queries · File system · External APIs · Crypto · Payments

## Reviewers
- Quality → **code-reviewer** agent
- Security → **security-reviewer** agent
- Architecture → **architect** agent

## Severity
| Level | Action |
|---|---|
| CRITICAL | Block merge |
| HIGH | Fix before merge |
| MEDIUM | Fix or document |
| LOW | Optional |

## Pre-Review Gates (all must pass before merge)
- [ ] All tests green (unit + feature)
- [ ] No linting errors (`pint` clean)
- [ ] PHPStan level 8 clean
- [ ] Security checklist completed
- [ ] Coverage ≥ 80%
- [ ] No hardcoded secrets or debug statements
- [ ] Migrations reversible (down method present)
