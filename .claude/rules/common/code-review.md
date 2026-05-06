---
description: Code review standards — when to review, severity levels, security triggers
alwaysApply: false
---

# Code Review

## When Review Is Mandatory
- After writing any non-trivial code block
- Before committing to shared branches
- For any security-sensitive change (auth, input, payments, file uploads)
- Before pull request merge

## Review Checklist
- [ ] Code is readable — names are self-explanatory
- [ ] Functions are under 50 lines
- [ ] No magic numbers or unexplained constants
- [ ] Nesting depth ≤ 4
- [ ] Error handling is present at boundaries
- [ ] No hardcoded secrets or debug statements
- [ ] Test coverage is maintained

## Security Triggers — Escalate to security-reviewer agent
Escalate immediately if the change touches:
- Authentication or session management
- User input processing
- Database queries
- File system operations
- External API calls
- Cryptography
- Payment flows

## Severity Framework
| Level | Action |
|---|---|
| CRITICAL | Block merge — fix before anything else |
| HIGH | Must fix before merge |
| MEDIUM | Fix if feasible, document if not |
| LOW | Optional — suggestion only |

## Language-Specific Reviewers
For deep review of specific languages, use the relevant agent:
- General quality → **code-reviewer** agent
- Security → **security-reviewer** agent
- Architecture → **architect** agent
