# CLAUDE.md

## Ekip Yapısı

```
Kullanıcı → Team Lead
  ├─► Backend (patterns/backend-developer.md)
  ├─► Frontend (patterns/frontend-developer.md)
  ├─► QA (patterns/qa-engineer.md)
  ├─► DevOps (patterns/devops-agent.md)
  ├─► Architect (agents/architect.md)
  └─► Security Reviewer (agents/security-reviewer.md)
```

Kullanıcı yalnızca team-lead ile konuşur. Team-lead görevi analiz eder, delege eder, raporlar.

---

## Proje Bağlamı

**Ne:** Yemek Tarifi uygulaması — fotoğraf yükle, AI tarif analizi yap, öneri al.

**Yığın:**
- Backend: Laravel 13, PHP 8.4, Pest, PHPStan lvl 8
- Frontend: Vue 3, Inertia.js, Tailwind CSS
- AI: `laravel/ai` + Groq (`meta-llama/llama-4-scout-17b-16e-instruct`)
- Altyapı: Docker monorepo — `app` (php-fpm) / `nginx` (8080) / `mysql:8.0` / `redis:7` / `queue`
- Frontend build: host'ta `npm run build` → volume üzerinden serve

**Mimari:**
- Monorepo: `backend/` + `frontend/` aynı repoda
- Agent: `App\Ai\Agents\RecipeAnalyzerAgent` (structured output)
- SESSION_ENCRYPT=true, Redis requirepass, ayrı DB_ROOT_PASSWORD
- Renk paleti: Primary `#2D6A4F`, Accent `#95D5B2`
- `GROQ_API_KEY` `.env`'e girilmeli. Production: `docker-compose.prod.yml`

---

## Slash Komutları

| Komut | Açıklama |
|---|---|
| `/backend` | Laravel controller/service/repository/test yaz |
| `/frontend` | Vue component / Inertia sayfası yaz |
| `/qa` | Pest feature veya unit test yaz |
| `/devops` | Docker / nginx / CI yapılandırması |
| `/architect` | ADR üret, trade-off analizi yap |
| `/security-review` | OWASP checklist, güvenlik açığı tara |
| `/laravel-tdd` | TDD döngüsü: RED → GREEN → REFACTOR |
| `/laravel-patterns` | Repository/DTO/Action pattern uygula |
| `/laravel-security` | Laravel güvenlik kontrolü (auth, input, query) |
| `/laravel-verify` | Mevcut kodu Laravel standartlarına göre doğrula |
| `/regression-test` | Değişiklik sonrası regresyon test çalıştır |
| `/workspace` | Çalışma alanı ve oturum yönetimi |
| `/task` | Görev planla ve takip et |
| `/github-workflow` | PR / branch / commit işlemleri |
| `/compact` | Context geçmişini özetle (her ~15 mesajda çalıştır) |

---

## `.claude/` Dizin Yapısı

```
.claude/
├── rules/
│   ├── common/
│   │   ├── core.md        ← Kodlama stili + Serena araç hiyerarşisi (alwaysApply: true)
│   │   ├── patterns.md    ← Tasarım desenleri + performans (kod yazarken aktif)
│   │   ├── workflow.md    ← Git workflow, geliştirme süreci, code review
│   │   ├── security.md    ← Güvenlik checklist (alwaysApply: true)
│   │   └── testing.md     ← TDD, coverage, Pest kuralları
│   └── php/
│       └── php.md         ← PSR-12, DTOs, Repository, Pest — *.php glob ile aktif
├── skills/                ← Slash komut implementasyonları (/backend, /qa vb.)
├── agents/                ← Özel ajan tanımları (architect, security-reviewer)
└── hooks/
    └── install.sh         ← Pre-commit + pre-push hook kurulumu
```

---

## Git Hook Kurulumu (bir kez)

```bash
bash .claude/hooks/install.sh
```
