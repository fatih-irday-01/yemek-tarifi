# Yemek Tarifi — AI-Powered Recipe Analyzer

> **Built with zero manually written lines of code.** Every controller, migration, test, and Vue component in this repository was generated through a structured AI agent team workflow orchestrated by [Claude Code](https://claude.ai/code) CLI, guided by 15 years of backend engineering principles encoded as machine-readable rules.

---

## What It Does

Upload a food photograph. The application identifies the dish, extracts all ingredients, generates a step-by-step recipe in Turkish, estimates cooking time and servings — and stores the analysis history under your account.

The core intelligence is a structured-output AI agent (`RecipeAnalyzerAgent`) backed by Meta's **Llama 4 Scout** vision model via Groq's ultra-low-latency inference API. Analysis runs as a queued background job; the frontend polls for completion status without blocking the UI.

---

## Architecture Overview

```
┌─────────────────────────────────────────────────────────────┐
│  Vue 3 + Inertia.js + Tailwind CSS  (SSR-free SPA feel)    │
│  Pages: Welcome · Dashboard · Upload · Show · History       │
└──────────────────────┬──────────────────────────────────────┘
                       │ HTTP (Inertia protocol)
┌──────────────────────▼──────────────────────────────────────┐
│  Laravel 13 / PHP 8.4  ·  nginx:8080                       │
│                                                              │
│  RecipeAnalysisController ──► AnalyzeRecipePhotoAction      │
│         │                            │                       │
│         │                   AnalyzeRecipePhotoJob (queue)   │
│         │                            │                       │
│         │                   RecipeAnalyzerAgent             │
│         │                   #[Provider(Groq)]               │
│         │                   #[Model(llama-4-scout-17b)]     │
│         │                            │                       │
│         │                   HasStructuredOutput → JSON      │
│         ▼                                                    │
│  RecipeAnalysisRepository ──► RecipeAnalysis (Eloquent)     │
└──────────────────────────────────┬──────────────────────────┘
                                   │
              ┌────────────────────┼────────────────────┐
              ▼                    ▼                    ▼
          MySQL 8.0           Redis 7            Queue Worker
```

### Key Layers

| Layer | Class | Responsibility |
|---|---|---|
| Controller | `RecipeAnalysisController` | HTTP transport, validation delegation, status polling |
| Action | `AnalyzeRecipePhotoAction` | Single-purpose, DB-transactional orchestration |
| Job | `AnalyzeRecipePhotoJob` | Async queue execution |
| AI Agent | `RecipeAnalyzerAgent` | Groq Vision structured output (JSON schema enforcement) |
| DTO | `RecipeAnalysisResult` | Typed data transfer across service boundaries |
| Repository | `RecipeAnalysisRepository` | Eloquent persistence behind interface |
| Enum | `AnalysisStatus` | `pending → processing → completed → failed` state machine |

---

## Tech Stack

### Backend
| | |
|---|---|
| Runtime | PHP 8.4 |
| Framework | Laravel 13 |
| AI SDK | `laravel/ai ^0.6.6` |
| Vision Model | `meta-llama/llama-4-scout-17b-16e-instruct` via Groq |
| Auth | Laravel Breeze + Sanctum |
| Testing | Pest 4 + Pest Laravel Plugin |
| Static Analysis | PHPStan level 8 |
| Code Style | PSR-12 enforced via Laravel Pint |

### Frontend
| | |
|---|---|
| Framework | Vue.js 3 |
| Routing | Inertia.js 2 (no API, no client-side router) |
| Styling | Tailwind CSS 3 · Primary `#2D6A4F` · Accent `#95D5B2` |
| Build | Vite 8 + `laravel-vite-plugin` |
| HTTP | Axios + Ziggy (named routes in JS) |

### Infrastructure
| Service | Image |
|---|---|
| App (php-fpm) | Custom Dockerfile |
| Web | nginx:1.27-alpine — port **8080** |
| Database | mysql:8.0 |
| Cache / Queue | redis:7-alpine |

---

## Installation

### Prerequisites
- Docker + Docker Compose
- Node.js 20+ (for frontend build on host)
- A [Groq API key](https://console.groq.com)

### 1. Clone and configure environment

```bash
git clone <repo-url> yemek-tarifi
cd yemek-tarifi
cp backend/.env.example backend/.env
```

Edit `backend/.env` and set:

```env
GROQ_API_KEY=your_key_here
DB_PASSWORD=your_db_password
DB_ROOT_PASSWORD=your_root_password
SESSION_ENCRYPT=true
```

### 2. Install Git hooks (once)

```bash
bash .claude/hooks/install.sh
```

Activates pre-commit code review and pre-push secret scanning.

### 3. Build frontend assets

```bash
cd backend
npm install
npm run build
```

### 4. Start Docker services

```bash
docker-compose up -d
```

### 5. Run migrations and seed

```bash
docker-compose exec app php artisan migrate --seed
```

App is live at **http://localhost:8080**

### Production

```bash
docker-compose -f docker-compose.prod.yml up -d
```

---

## API / Routes

```
GET  /                        Welcome (guest)
GET  /dashboard               Dashboard (auth)
GET  /recipes/upload          Upload form
POST /recipes/analyze         Submit photo → queues analysis job   [throttle: 10/min]
GET  /recipes/{id}            Analysis result page
GET  /api/analyses/{id}/status  Polling endpoint → { status, data }
GET  /history                 Analysis history list
```

---

## How the Vision AI Works

```php
#[Provider(Lab::Groq)]
#[Model('meta-llama/llama-4-scout-17b-16e-instruct')]
final class RecipeAnalyzerAgent implements Agent, HasStructuredOutput
{
    public function schema(JsonSchema $schema): array
    {
        return [
            'food_name'    => $schema->string()->required(),
            'ingredients'  => $schema->array()->items($schema->string())->required(),
            'steps'        => $schema->array()->items($schema->string())->required(),
            'cooking_time' => $schema->string()->required(),
            'servings'     => $schema->integer()->required(),
        ];
    }
}
```

The agent enforces a strict JSON schema via `HasStructuredOutput`. The model receives the food photograph as a vision input and must respond with a valid structured object — no free-text, no markdown. If the model response doesn't conform to the schema, the job fails and the `AnalysisStatus` transitions to `failed`.

---

## Built with AI — Zero Manual Code

This project exists as a proof-of-concept for a **vibecoding workflow** at senior engineering quality: describe intent in natural language, let a structured AI agent team produce production-grade code, review the output the same way you'd review a junior developer's PR.

### The Agent Team

```
User (Team Lead)
  ├─► Backend Developer  — Laravel patterns, PSR-12, PHPDoc, DTOs, Repositories
  ├─► Frontend Developer — Vue 3, Inertia.js, Tailwind
  ├─► QA Engineer        — Pest tests, RED → GREEN → REFACTOR
  ├─► DevOps Agent       — Docker, nginx, CI/CD
  ├─► Architect          — ADRs, trade-off analysis, anti-pattern detection
  └─► Security Reviewer  — OWASP Top 10, input validation, secret scanning
```

### Serena MCP — Semantic Code Intelligence

[Serena MCP](https://github.com/oraios/serena) provides LSP-level symbol indexing over the entire codebase. Instead of reading files line-by-line, agents use a six-tier tool hierarchy:

```
1. find_symbol()               → locate classes, methods, properties
2. get_symbols_overview()      → directory/file structural outline
3. read_file(path, range)      → targeted line-range reads only
4. search_for_pattern()        → codebase-wide regex search
5. find_referencing_symbols()  → impact analysis before refactoring
6. grep (Bash)                 → last resort for YAML / config / docs
```

This hierarchy reduces context consumption by ~60% compared to naive file reads — the AI agent team can explore and modify a Laravel + Vue monorepo without exhausting the context window.

### Rules-as-Code

Engineering standards are stored as structured markdown in `.claude/rules/`, loaded automatically by Claude Code:

```
.claude/rules/
├── common/
│   ├── core.md        ← Coding style, design patterns, performance (always active)
│   ├── workflow.md    ← Git workflow, code review process, agent escalation paths
│   ├── security.md    ← Pre-commit security checklist (always active)
│   └── testing.md     ← TDD workflow, coverage thresholds, Pest conventions
└── php/
    └── php.md         ← PSR-12, PHPDoc, DTOs, Repository pattern, PHP 8.4 features
                          (activated on **/*.php glob match)
```

The PHP rules file alone encodes: constructor property promotion, backed enums with `EnumMethods` trait, `readonly` DTOs, `$fillable` mass-assignment protection, `password_hash()` enforcement, `RefreshDatabase` vs `DatabaseTransactions` selection criteria, and `assertInertia` usage — so the model doesn't have to guess.

### Development Session Example

```
User:   "Add recipe history with pagination"

Claude Code:
  1. Research  → scans RecipeAnalysis model, existing routes, History.vue
  2. Plan      → identifies affected layers: migration, repository, controller, Vue page
  3. TDD       → writes Pest feature test first (RED)
  4. Implement → Repository → Controller → Inertia response → Vue component (GREEN)
  5. Review    → security-reviewer checks new query for N+1 and mass-assignment
  6. Commit    → feat: add paginated recipe history endpoint
```

No manual code written. No context switching. The entire flow above — including the Pest test, the Eloquent query with eager-loading, the Inertia page component, and the PHPDoc blocks — was produced in a single Claude Code session.

---

## Development Commands

```bash
# Run tests
docker-compose exec app php artisan test

# Static analysis
docker-compose exec app ./vendor/bin/phpstan analyse

# Code style fix
docker-compose exec app ./vendor/bin/pint

# Tail logs
docker-compose exec app php artisan pail

# Queue worker (already running via docker-compose)
docker-compose exec queue php artisan queue:work
```

---

## License

MIT
