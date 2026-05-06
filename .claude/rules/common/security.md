---
description: Security — no hardcoded secrets, input validation, incident response
alwaysApply: true
---

<!-- Applies to: all files -->

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
- Secrets in `.env` or secret managers — never in code.
- Verify required env vars at application startup.
- Rotate any exposed credential immediately.

## Incident Response
- Stop work immediately.
- Escalate to **security-reviewer** agent.
- Fix critical issues before proceeding.
- Rotate exposed credentials.
- Audit codebase for similar patterns.
