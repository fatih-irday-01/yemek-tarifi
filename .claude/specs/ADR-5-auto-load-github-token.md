# ADR-5: Claude Başlangıcında GITHUB_PERSONAL_ACCESS_TOKEN Otomatik Yükle

## Karar

Claude Code her oturum başında `backend/.env` dosyasından `GITHUB_PERSONAL_ACCESS_TOKEN` değerini okur ve ortam değişkeni olarak set eder. İki katmanlı yaklaşım uygulanır:

1. **`UserPromptSubmit` hook** — `.claude/settings.json`'a eklenir; her oturumun ilk promptunda çalışır. Bash aracının persistent shell'i üzerinden token erişilebilir olur.
2. **Shell profili (`~/.zshrc`)** — MCP server'lar Claude başlarken (hook'tan önce) başladığından, MCP araçlarının token görebilmesi için `~/.zshrc`'ye de aynı export satırı eklenir.

## Gerekçe

- MCP GitHub araçları `GITHUB_PERSONAL_ACCESS_TOKEN` env var'ını bekler.
- Token `backend/.env`'de zaten var; çift yönetim gereksiz.
- Hooks subshell'de çalışır → MCP server'lar için yeterli değil → `~/.zshrc` tamamlar.
- Token gitignore'daki `.env`'den okunur; kaynak kodda hardcode edilmez.

## Etkilenen Bileşenler

- `.claude/settings.json` — UserPromptSubmit hook
- `~/.zshrc` — shell-level export (kullanıcı profili)

## Kabul Kriterleri

- [ ] Hook `.claude/settings.json`'da tanımlı
- [ ] `echo $GITHUB_PERSONAL_ACCESS_TOKEN` Bash aracında değer döner
- [ ] MCP GitHub araçları (issue, PR, branch) çalışır
