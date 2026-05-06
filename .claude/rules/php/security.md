---
description: PHP security rules — input validation, SQL injection, dependency audits, session hardening
globs: ["**/*.php"]
alwaysApply: false
---

# PHP Security

## Input Validation
- Validate ALL request input at the framework boundary (Form Requests).
- Treat query parameters, cookies, headers, and file metadata as untrusted until validated.
- Never use raw `$_GET`, `$_POST`, or `$request->input()` without validation rules.

## Database Security
- Use Eloquent ORM or parameterized queries — never concatenate user input into SQL.
- Guard Eloquent models with `$fillable` — never use `$guarded = []`.
- Manage mass-assignment explicitly.

## Authentication
- Use `password_hash()` / `password_verify()` — never `md5` or `sha1` for passwords.
- Regenerate session IDs after login and privilege escalation.
- Enforce CSRF protection on all state-changing requests (`VerifyCsrfToken` middleware).

## Dependencies
- Run `composer audit` in CI — block on HIGH/CRITICAL vulnerabilities.
- Verify new package maintainers before adoption.
- Never commit `.env` files or any file containing credentials.

## Output
- Escape template output by default — treat raw HTML rendering as requiring special justification.
- Redact PII from application logs.

## Reference
- For Laravel-specific security patterns: see `skills/laravel-security/SKILL.md`
