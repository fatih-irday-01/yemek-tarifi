---
description: Feature development workflow — research, plan, TDD, review, commit
alwaysApply: false
---

<!-- Applies to: all files -->

# Development Workflow

## Five-Phase Process (in order)

### 1. Research & Reuse
- Check for existing patterns in the codebase first.
- Confirm library API behavior before writing code.
- Prefer proven approaches over net-new code.

### 2. Plan
- Identify affected layers (DB, API, UI, infra).
- Define API contract before backend and frontend start.
- Architectural decisions → consult **architect** agent.

### 3. Test-Driven Development
Follow TDD — see `testing` rules.

### 4. Code Review
- Security-sensitive changes → run **security-reviewer** agent.
- CRITICAL/HIGH issues: fix before merge.
- MEDIUM issues: fix or document.

### 5. Commit & Push
- Commit messages explain WHY (see git-workflow rules).
- CI/CD must pass. Resolve conflicts before merge.

## Pre-Review Gates
- All tests green
- No linting errors
- No static analysis warnings (PHPStan/Psalm)
- Security checklist complete
- Coverage threshold met
