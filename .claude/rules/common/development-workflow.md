---
description: Feature development workflow — research first, plan, TDD, review, commit
alwaysApply: false
---

# Development Workflow

## Five-Phase Process (in order)

### 1. Research & Reuse
Before writing any code:
- Check if a pattern already exists in this codebase
- Review library documentation to confirm API behavior
- Prefer adopting a proven approach over writing net-new code

### 2. Plan
- Identify affected layers (DB, API, UI, infra)
- Define the API contract before backend and frontend start
- For architectural decisions, consult the **architect** agent

### 3. Test-Driven Development
Follow the RED → GREEN → REFACTOR cycle:
1. QA writes failing tests first
2. Backend/Frontend implements to make them pass
3. Refactor while keeping tests green
4. Verify 80%+ coverage

### 4. Code Review
- Run **security-reviewer** agent on any security-sensitive change
- Address CRITICAL and HIGH issues before merge
- Fix or document MEDIUM issues

### 5. Commit & Push
- Write commit messages explaining WHY (see git-workflow rules)
- Ensure CI/CD passes
- Resolve conflicts before requesting merge

## Pre-Review Gates
All of these must pass before requesting human review:
- [ ] All tests green
- [ ] No linting errors
- [ ] No static analysis warnings (PHPStan/Psalm)
- [ ] Security checklist complete
- [ ] Coverage threshold met
