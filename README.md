# AI Project Tools

Laravel + Vue.js projeleri için hazır AI geliştirme ekibi. Claude Code üzerinde çalışır.

Bir görevi sadece yazarsın — backend, frontend, test ve güvenlik otomatik organize olur.

---

## Ne Yapar?

Normalde Claude Code'a bir şey sorduğunda tek bir AI yanıt verir. Bu araç, o tek AI'ı **uzmanlaşmış bir ekibe** dönüştürür:

```
Sen: "/task kullanıcı silme işlemini loglayalım"
     ↓
Team Lead → görevi analiz eder, katmanlara böler
     ├─► Backend  → migration + model + repository + endpoint
     ├─► Frontend → log listesi sayfası + API entegrasyonu
     └─► QA       → Behat / Playwright testleri yazar
```

Her agent kendi uzmanlığında çalışır, kendi kurallarına uyar.

---

## Kurulum

### 1. Bu repoyu klonla

```bash
git clone <repo-url> my-project
cd my-project
```

### 2. Git hook'larını aktif et

```bash
bash .claude/hooks/install.sh
```

Bir kez çalıştırılır. Commit ve push'larda otomatik güvenlik kontrolleri devreye girer.

### 3. Workspace modunu yapılandır

`.claude/workspace.conf` dosyasını projenin yapısına göre düzenle:

```bash
# Monorepo (backend/ ve frontend/ aynı repoda)
WORKSPACE_MODE=monorepo
BACKEND_PATH=./backend
FRONTEND_PATH=./frontend

# Ayrı repolar
WORKSPACE_MODE=multirepo
BACKEND_REPO=/path/to/api
FRONTEND_REPO=/path/to/frontend
```

### 4. Proje bağlamını tanımla

`CLAUDE.md` dosyasının altındaki **Proje Bağlamı** bölümünü doldur:

```markdown
### Proje Nedir?
Sipariş yönetim sistemi REST API'si.

### Teknoloji Yığını
Backend: Laravel 11, MySQL, Redis
Frontend: Vue.js 3, Inertia.js

### Mimari Notlar
Multi-tenant yapı. Her istek company_id ile izole edilmeli.
```

---

## Kullanım

### Tam Görev — `/task`

Birden fazla katmanı etkileyen işler için kullanılır. Team-lead her şeyi organize eder.

```
/task kullanıcı davet sistemi ekleyelim
/task ödeme geçmişi sayfası
/task rol bazlı yetkilendirme
```

Team-lead görevi alır, `workspace.sh` otomatik çalışır, terminal 4 pane'e bölünür:

```
┌─────────────┬─────────────┐
│  Team Lead  │   Backend   │
├─────────────┼─────────────┤
│  Frontend   │     QA      │
└─────────────┴─────────────┘
```

Her pane kendi görevini alır ve çalışmaya başlar.

### Tek Katman — Doğrudan Agent

Sadece belirli bir katmanda iş varsa:

```
/backend yeni migration ekle
/frontend kullanıcı tablosunu güncelle
/qa login testlerini genişlet
/devops redis servisi ekle
```

### Uzman Komutlar

```
/architect  → Mimari karar ver, ADR üret
             Örnek: /architect event sourcing kullanmalı mıyız?

/security-review → Güvenlik taraması yap
             Örnek: /security-review app/Http/Controllers/

/laravel-tdd → TDD workflow uygula (test önce)
             Örnek: /laravel-tdd POST /api/v1/orders endpoint'i

/laravel-verify → Deploy öncesi 7 aşamalı kontrol
             Örnek: /laravel-verify

/regression-test → Bug'ı kilitleyen test yaz
             Örnek: /regression-test sipariş null dönüyor

/laravel-patterns → Mimari pattern rehberi
/laravel-security → Güvenlik sertleştirme rehberi
```

---

## Klasör Yapısı

```
.claude/
├── agents/
│   ├── team-lead.md          ← Ana orkestratör (tek muhatap)
│   ├── architect.md          ← Mimari kararlar, ADR
│   └── security-reviewer.md  ← OWASP güvenlik taraması
│
├── patterns/                 ← Geliştirici rolleri
│   ├── backend-developer.md  ← Laravel kuralları
│   ├── frontend-developer.md ← Vue.js kuralları
│   ├── qa-engineer.md        ← Behat / Playwright
│   └── devops-agent.md       ← Docker / CI-CD
│
├── commands/                 ← Slash command'ların içeriği
│   ├── task.md, backend.md, frontend.md, qa.md, devops.md
│   ├── workspace.md          ← 4-pane tmux workspace
│   ├── architect.md, security-review.md
│   ├── laravel-tdd.md, laravel-patterns.md
│   ├── laravel-security.md, laravel-verify.md
│   └── regression-test.md
│
├── rules/
│   ├── common/               ← Her zaman aktif (coding-style, security, testing…)
│   └── php/                  ← PHP dosyalarında aktif (PSR-12, güvenlik, pattern'lar)
│
├── hooks/
│   ├── pre-commit            ← Commit öncesi code review
│   └── pre-push              ← Push öncesi secret taraması (bloklayıcı)
│
├── scripts/
│   ├── workspace.sh          ← 4-pane tmux workspace başlatıcı
│   └── workspace-cleanup.sh  ← Monorepo worktree temizliği
│
├── memory/
│   └── session.md            ← Oturumlar arası hafıza
│
├── workspace.conf            ← Workspace modu (monorepo / multirepo / simple)
└── settings.json             ← İzinler ve Claude Code ayarları

CLAUDE.md                     ← Claude'un her oturumda okuduğu talimat dosyası
.mcp.json                     ← MCP sunucu tanımları
```

---

## Otomatik Çalışan Şeyler

Bunları ayrıca söylemene gerek yok — arka planda kendiliğinden devreye girer:

| Ne | Ne Zaman | Etki |
|---|---|---|
| Kodlama kuralları (`rules/common/`) | Her zaman | Isimlendirme, güvenlik, test zorunlulukları |
| PHP kuralları (`rules/php/`) | PHP dosyası açıldığında | PSR-12, strict_types, pattern'lar |
| Security Reviewer | Yeni endpoint veya auth değişikliği | OWASP taraması |
| Architect | Mimari karar gerektiğinde | Trade-off analizi, ADR |
| `pre-commit` hook | Her git commit | Code review özeti |
| `pre-push` hook | Her git push | Secret taraması, bulursa bloklar |

---

## Git Hooks

```bash
# Kurulum (bir kez)
bash .claude/hooks/install.sh

# Kaldırma
git config --unset core.hooksPath
```

---

## MCP Sunucu Ekleme

`.mcp.json` dosyasına yeni sunucu ekle, Claude Code'u yeniden başlat:

```json
{
  "mcpServers": {
    "github": {
      "command": "npx",
      "args": ["-y", "@modelcontextprotocol/server-github"]
    }
  }
}
```

Token için: GitHub → Settings → Developer Settings → Personal Access Tokens → `repo` scope.
