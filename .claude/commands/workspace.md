# Workspace

4-pane geliştirme workspace'ini başlatır. Team-lead tarafından çok katmanlı görevlerde otomatik tetiklenir.

$ARGUMENTS

---

```
┌─────────────┬─────────────┐
│  Team Lead  │   Backend   │
├─────────────┼─────────────┤
│  Frontend   │     QA      │
└─────────────┴─────────────┘
```

## Mod Tespiti (workspace.conf'a göre)

| Mod | Ne Zaman | Nasıl Çalışır |
|---|---|---|
| `monorepo` | Aynı repoda `backend/` + `frontend/` var | Her göreve git worktree açar, izole branch'ler |
| `multirepo` | Ayrı repolar (`BACKEND_REPO` + `FRONTEND_REPO` tanımlı) | Her pane kendi repo dizinine gider |
| `simple` | Tek repo, alt dizin yok | Tüm pane'ler aynı dizinde |

## Çalıştırma

```bash
# Görevsiz başlat
bash .claude/scripts/workspace.sh

# Görevle başlat (agent'lara otomatik iletilir)
bash .claude/scripts/workspace.sh "kullanıcı silme işlemini loglayalım"
```

## Monorepo — İş Bitiminde Temizlik

```bash
# Sadece worktree'leri sil
bash .claude/scripts/workspace-cleanup.sh

# Önce main'e merge et, sonra sil
bash .claude/scripts/workspace-cleanup.sh --merge
```

## İlk Kurulum (Proje Başında Bir Kez)

`.claude/workspace.conf` dosyasını projene göre doldur:

```bash
# Monorepo için (backend/ ve frontend/ alt dizinleri var)
WORKSPACE_MODE=monorepo
BACKEND_PATH=./backend
FRONTEND_PATH=./frontend

# Multirepo için (ayrı repolar)
WORKSPACE_MODE=multirepo
BACKEND_REPO=/absolute/path/to/api
FRONTEND_REPO=/absolute/path/to/frontend
```
