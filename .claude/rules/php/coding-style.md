---
description: PHP coding style — PSR-12, strict_types, type hints, PHP 8.x features, PHPDoc
globs: ["**/*.php"]
alwaysApply: false
---

<!-- Applies to: **/*.php -->

# PHP Coding Style

## Standards
- Follow **PSR-12**. Use **Laravel Pint** to auto-format.
- All files begin with `declare(strict_types=1);`
- Type hints on all parameters, return types, and class properties.
- `readonly` properties and `final readonly class` for DTOs and Actions.
- DB columns: `snake_case` (overrides common naming for database layer).

## Imports
- Always explicit `use` statements — no global namespace assumptions.
- Group: PHP core → Laravel → App.

## Error Handling
- Throw exceptions for error conditions — never return `null` or `false` to signal failure.
- Convert Form Requests into validated DTOs before passing to services.

## Static Analysis
- Run **PHPStan** (level 8) or **Psalm** on all PHP code.
- Add Composer scripts for `lint` and `analyse`.

## PHP 8.x Features (use actively)
- Constructor property promotion
- Named arguments
- Match expressions
- Readonly properties
- Enums (backed, with EnumMethods trait)
- Nullsafe operator `?->`

## PHPDoc — Required on all classes and methods
- **Class**: `@package` with full namespace
- **Method**: `@param TypeHint $name` per parameter, `@return TypeHint`
- **Model**: `@property TypeHint $column` for all columns
- **Constructor**: `@param` for each injected dependency
- Overrides the system-wide "no comments" rule — PHPDoc is API documentation (PSR-5), not inline comment.
