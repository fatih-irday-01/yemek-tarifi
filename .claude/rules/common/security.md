---
description: Security requirements — no hardcoded secrets, input validation, incident response protocol
alwaysApply: true
---

# Security

## Pre-Commit Checklist (mandatory)
- [ ] No hardcoded secrets (API keys, passwords, tokens, connection strings)
- [ ] All user inputs validated at system boundaries
- [ ] No SQL string concatenation — use parameterized queries / ORM
- [ ] Output escaped where rendered as HTML (XSS prevention)
- [ ] CSRF protection on state-changing requests
- [ ] Authentication verified on protected endpoints
- [ ] Error messages do not expose stack traces or internal details
- [ ] All API endpoints have rate limiting

## Secret Handling
- Secrets live in `.env` files or secret managers — never in code.
- Verify required env vars exist at application startup.
- Rotate any credential that may have been exposed immediately.

## Incident Response
When a vulnerability is discovered:
1. Stop work immediately.
2. Escalate to **security-reviewer** agent.
3. Fix critical issues before proceeding.
4. Rotate exposed credentials.
5. Audit the entire codebase for similar patterns.
