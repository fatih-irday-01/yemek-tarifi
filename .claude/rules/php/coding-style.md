---
description: PHP coding style — PSR-12, strict_types, type hints, immutable DTOs
globs: ["**/*.php"]
alwaysApply: false
---

# PHP Coding Style

## Standards
- Follow **PSR-12** formatting. Use **Laravel Pint** to auto-format.
- All application files must begin with `declare(strict_types=1);`
- Use type hints on all parameters, return types, and class properties.
- Use `readonly` properties and `final readonly class` for DTOs and Actions.

## Naming
- Classes: `PascalCase`
- Methods/variables: `camelCase`
- DB columns: `snake_case`
- Constants: `UPPER_SNAKE_CASE`

## Imports
- Always use explicit `use` statements — no global namespace assumptions.
- Group `use` statements: PHP core → Laravel → App.

## Error Handling
- Throw exceptions for error conditions — never return `null` or `false` to signal failure.
- Convert framework inputs (Form Requests) into validated DTOs before passing to services.

## Static Analysis
- Run **PHPStan** (level 8) or **Psalm** on all PHP code.
- Add Composer scripts for `lint` and `analyse` and commit them.

## PHP 8.x Features (use actively)
- Constructor property promotion
- Named arguments
- Match expressions
- Readonly properties
- Enums (backed, with EnumMethods trait)
- Nullsafe operator `?->`

## PHPDoc — Zorunlu

PHPDoc blokları tüm class ve metodlarda zorunludur. Bu inline comment değil, PHP API dokümantasyonu standardıdır (PSR-5). Sistem genelindeki "no comments" kuralı PHP dosyalarında PHPDoc için geçerli değildir.

- **Class**: `@package` ile tam namespace
- **Method**: her parametre için `@param TypeHint $name`, `@return TypeHint`
- **Model**: tüm kolonlar için `@property TypeHint $column`
- **Constructor**: inject edilen her bağımlılık için `@param`

```php
/**
 * Class RecipeService
 *
 * @package App\Services
 */
class RecipeService
{
    /**
     * @param RecipeAnalysis $analysis
     * @return AnalysisResult
     */
    public function analyse(RecipeAnalysis $analysis): AnalysisResult
    {
        // ...
    }
}
```
