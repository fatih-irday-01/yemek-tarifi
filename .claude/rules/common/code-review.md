---
description: Code review standards — when to review, severity levels, security triggers
alwaysApply: false
---

<!-- Applies to: all files -->

# Code Review

## When Review Is Mandatory
- After any non-trivial code block
- Before committing to shared branches
- For security-sensitive changes (auth, input, payments, file uploads)
- Before pull request merge

## Review Checklist
- [ ] Names are self-explanatory
- [ ] Error handling present at boundaries
- [ ] No hardcoded secrets or debug statements
- [ ] Test coverage maintained

## Security Triggers — Escalate to security-reviewer agent
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
| CRITICAL | Block merge |
| HIGH | Fix before merge |
| MEDIUM | Fix or document |
| LOW | Optional suggestion |

## Reviewers
- Quality → **code-reviewer** agent
- Security → **security-reviewer** agent
- Architecture → **architect** agent
