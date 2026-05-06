---
description: PHP security — input validation, SQL injection prevention, session hardening, dependency audits
globs: ["**/*.php"]
alwaysApply: false
---

<!-- Applies to: **/*.php -->

# PHP Security

## Input Validation
- Validate ALL input at the framework boundary (Form Requests).
- Treat query parameters, cookies, headers, and file metadata as untrusted.
- Never use raw `$_GET`, `$_POST`, or `$request->input()` without validation rules.

## Database Security
- Eloquent ORM or parameterized queries — never concatenate user input into SQL.
- Guard models with `$fillable` — never use `$guarded = []`.
- Manage mass-assignment explicitly.

## Authentication
- `password_hash()` / `password_verify()` — never `md5` or `sha1`.
- Regenerate session IDs after login and privilege escalation.
- Enforce CSRF on all state-changing requests (`VerifyCsrfToken` middleware).

## Dependencies
- `composer audit` in CI — block on HIGH/CRITICAL vulnerabilities.
- Verify new package maintainers before adoption.
- Never commit `.env` files or credentials.

## Output
- Escape template output by default.
- Redact PII from application logs.
