# CLAUDE.md

Bu dosya Claude Code'un her oturumda okuduğu talimat dosyasıdır.

---

## Ekip Yapısı

```
Kullanıcı
  └─► Team Lead (agents/team-lead.md)
        ├─► Backend Developer  (patterns/backend-developer.md)
        ├─► Frontend Developer (patterns/frontend-developer.md)
        ├─► QA Engineer        (patterns/qa-engineer.md)
        ├─► DevOps Engineer    (patterns/devops-agent.md)
        ├─► Architect          (agents/architect.md)        ← mimari kararlar
        └─► Security Reviewer  (agents/security-reviewer.md) ← güvenlik taraması
```

**Kullanıcı yalnızca team-lead ile konuşur.** Team-lead görevi analiz eder, uygun agent'lara delege eder, sonucu raporlar.

---

## Klasör Yapısı

```
.claude/
├── agents/           ← orchestrator + uzman sub-agent'lar
├── patterns/         ← geliştirici kadrosu (Laravel, Vue.js, QA, DevOps)
├── commands/         ← tüm slash command'lar (içerik burada, ayrı skill klasörü yok)
├── rules/
│   ├── common/       ← her zaman aktif evrensel kurallar (alwaysApply: true)
│   └── php/          ← PHP dosyalarında otomatik devreye girer (globs: **/*.php)
├── hooks/            ← git hooks (pre-commit review, pre-push security scan)
├── scripts/          ← workspace.sh, workspace-cleanup.sh
├── memory/           ← oturumlar arası hafıza (session.md)
├── specs/            ← feature specification'ları
├── workspace.conf    ← workspace modu ve yolları (monorepo/multirepo/simple)
└── settings.json     ← Claude Code izinleri ve hooks
```

---

## Slash Commands

### Ekip Komutları
| Komut | Ne yapar |
|---|---|
| `/task <görev>` | Team-lead devreye girer — analiz eder, böler, tüm katmanları implement eder |
| `/backend <görev>` | Yalnızca backend developer |
| `/frontend <görev>` | Yalnızca frontend developer |
| `/qa <görev>` | Yalnızca QA engineer |
| `/devops <görev>` | Yalnızca DevOps engineer |
| `/workspace` | 4-pane tmux workspace açar (monorepo/multirepo otomatik tespit) |

### Uzman Komutları
| Komut | Ne yapar |
|---|---|
| `/architect <karar>` | Mimari analiz + ADR üretimi |
| `/security-review <hedef>` | OWASP Top 10 güvenlik taraması |
| `/laravel-tdd <endpoint>` | TDD workflow — test önce, sonra implementasyon |
| `/laravel-patterns` | Laravel mimari pattern rehberi |
| `/laravel-security` | Laravel güvenlik sertleştirme rehberi |
| `/laravel-verify` | Deploy öncesi 7 aşamalı verification loop |
| `/regression-test <bug>` | Bulunan bug'ı kilitleyen regression testi |

---

## Rules Sistemi

`rules/` altındaki dosyalar Claude Code tarafından otomatik yüklenir:
- **`common/`** — `alwaysApply: true` → her oturumda aktif
- **`php/`** — `globs: ["**/*.php"]` → PHP dosyası context'e girince aktif

Manuel müdahale gerekmez.

---

## Workspace Modu

`.claude/workspace.conf` dosyasına göre otomatik belirlenir:

| Mod | Ne zaman | Davranış |
|---|---|---|
| `monorepo` | Aynı repoda `backend/` + `frontend/` var | İzole git worktree branch'leri açar |
| `multirepo` | `BACKEND_REPO` + `FRONTEND_REPO` tanımlı | Her pane kendi repo dizinine gider |
| `simple` | Yukarıdakiler yoksa | Tüm pane'ler aynı dizinde |

---

## Memory Sistemi

Team-lead her göreve başlarken `.claude/memory/session.md` dosyasını okur.
Görev bitiminde aynı dosyaya özet yazar. Bir sonraki oturumda kaldığı yerden devam eder.

---

## Git Hook Kurulumu

Proje klonlandıktan sonra **bir kez** çalıştır:

```bash
bash .claude/hooks/install.sh
```

- `pre-commit` → her commit öncesi code review (bilgilendirme)
- `pre-push` → her push öncesi secret/key taraması (bloklayıcı)

---

## MCP Sunucu Ekleme

`.mcp.json` dosyasını düzenle, Claude Code'u yeniden başlat:

```json
{
  "mcpServers": {
    "git": {
      "command": "npx",
      "args": ["-y", "@modelcontextprotocol/server-git", "--repository", "."]
    }
  }
}
```

---

## Proje Bağlamı

> Yeni projede bu bölümü doldur.

### Proje Nedir?

### Teknoloji Yığını

### Mimari Notlar

### Özel Kurallar
