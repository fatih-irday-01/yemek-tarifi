#!/bin/bash
# ─────────────────────────────────────────────────────────────────
# workspace.sh — 4-pane AI geliştirme workspace'i
#
# Layout:
#   ┌─────────────┬─────────────┐
#   │  Team Lead  │   Backend   │
#   ├─────────────┼─────────────┤
#   │  Frontend   │     QA      │
#   └─────────────┴─────────────┘
#
# Mod:
#   monorepo  → git worktree ile izole branch'ler oluşturur
#   multirepo → her pane kendi repo dizinine gider
#   simple    → worktree yok, tüm pane'ler aynı dizinde
#
# Kullanım:
#   bash .claude/scripts/workspace.sh "görev açıklaması"
# ─────────────────────────────────────────────────────────────────

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(cd "$SCRIPT_DIR/../.." && pwd)"
CONF="$PROJECT_ROOT/.claude/workspace.conf"

# ── Varsayılanlar ─────────────────────────────────────────────────
WORKSPACE_MODE="auto"
BACKEND_PATH="./backend"
FRONTEND_PATH="./frontend"
BACKEND_REPO=""
FRONTEND_REPO=""
WORKTREE_DIR=".worktrees"
CLAUDE_WAIT=8

# Konfigürasyonu yükle
[ -f "$CONF" ] && source "$CONF"

# İlk argüman görev açıklaması
TASK="${1:-}"
FEATURE_BRANCH="feature/ws-$(date +%Y%m%d-%H%M%S)"

# ── Mod tespiti ───────────────────────────────────────────────────
if [ "$WORKSPACE_MODE" = "auto" ]; then
    if git -C "$PROJECT_ROOT" rev-parse --git-dir > /dev/null 2>&1 \
       && [ -d "$PROJECT_ROOT/$BACKEND_PATH" ] \
       && [ -d "$PROJECT_ROOT/$FRONTEND_PATH" ]; then
        WORKSPACE_MODE="monorepo"
    elif [ -n "$BACKEND_REPO" ] && [ -n "$FRONTEND_REPO" ]; then
        WORKSPACE_MODE="multirepo"
    else
        WORKSPACE_MODE="simple"
    fi
fi

echo ""
echo "  Workspace modu : $WORKSPACE_MODE"
[ -n "$TASK" ] && echo "  Görev          : $TASK"
echo ""

# ── Çalışma dizinleri ─────────────────────────────────────────────
BACKEND_WD="$PROJECT_ROOT"
FRONTEND_WD="$PROJECT_ROOT"
QA_WD="$PROJECT_ROOT"

setup_monorepo() {
    local bk_wt="$PROJECT_ROOT/$WORKTREE_DIR/backend"
    local fe_wt="$PROJECT_ROOT/$WORKTREE_DIR/frontend"

    mkdir -p "$PROJECT_ROOT/$WORKTREE_DIR"
    git -C "$PROJECT_ROOT" worktree prune 2>/dev/null || true

    # Eski worktree'leri temizle
    [ -d "$bk_wt" ] && git -C "$PROJECT_ROOT" worktree remove --force "$bk_wt" 2>/dev/null || true
    [ -d "$fe_wt" ] && git -C "$PROJECT_ROOT" worktree remove --force "$fe_wt" 2>/dev/null || true

    git -C "$PROJECT_ROOT" worktree add -b "${FEATURE_BRANCH}-backend"  "$bk_wt"  HEAD
    git -C "$PROJECT_ROOT" worktree add -b "${FEATURE_BRANCH}-frontend" "$fe_wt"  HEAD

    BACKEND_WD="$bk_wt"
    FRONTEND_WD="$fe_wt"
    QA_WD="$bk_wt"

    echo "  Backend  branch : ${FEATURE_BRANCH}-backend"
    echo "  Frontend branch : ${FEATURE_BRANCH}-frontend"
    echo ""
}

case "$WORKSPACE_MODE" in
    monorepo)
        setup_monorepo
        ;;
    multirepo)
        BACKEND_WD="$BACKEND_REPO"
        FRONTEND_WD="$FRONTEND_REPO"
        QA_WD="$BACKEND_REPO"
        echo "  Backend  dir : $BACKEND_WD"
        echo "  Frontend dir : $FRONTEND_WD"
        echo ""
        ;;
    simple)
        echo "  Tüm pane'ler : $PROJECT_ROOT"
        echo ""
        ;;
esac

# ── tmux kontrolü ─────────────────────────────────────────────────
if [ -z "${TMUX:-}" ]; then
    echo "Hata: Bu script bir tmux oturumu içinde çalışmalı."
    echo "Önce 'tmux' komutunu çalıştır, sonra tekrar dene."
    exit 1
fi

# ── Pane oluştur ──────────────────────────────────────────────────
PANE0="${TMUX_PANE:-$(tmux display-message -p '#{pane_id}')}"
WINDOW=$(tmux display-message -p -t "$PANE0" '#{session_name}:#{window_index}')

# Bu window'daki diğer pane'leri temizle
for pane in $(tmux list-panes -t "$WINDOW" -F '#{pane_id}'); do
    [ "$pane" != "$PANE0" ] && tmux kill-pane -t "$pane" 2>/dev/null || true
done

# 2x2 layout
#   PANE0 (sol-üst)  | PANE1 (sağ-üst)
#   PANE2 (sol-alt)  | PANE3 (sağ-alt)
PANE1=$(tmux split-window -h -t "$PANE0" -P -F '#{pane_id}')
PANE2=$(tmux split-window -v -t "$PANE0" -P -F '#{pane_id}')
PANE3=$(tmux split-window -v -t "$PANE1" -P -F '#{pane_id}')
tmux select-layout -t "$WINDOW" tiled

# Pane başlıkları
tmux set-option -t "$WINDOW" pane-border-status top
BORDER_FMT="#{?#{==:#{pane_id},$PANE0}, Team Lead ,#{?#{==:#{pane_id},$PANE1}, Backend ,#{?#{==:#{pane_id},$PANE2}, Frontend , QA }}}"
tmux set-option -t "$WINDOW" pane-border-format "$BORDER_FMT"

# ── Agent başlatma ────────────────────────────────────────────────
start_agent() {
    local pane="$1"
    local workdir="$2"
    local role_cmd="$3"

    tmux send-keys -t "$pane" "cd \"$workdir\" && claude" Enter
    sleep "$CLAUDE_WAIT"
    tmux send-keys -t "$pane" "$role_cmd" Enter
}

TASK_MSG="${TASK:+$TASK}"
FALLBACK="Workspace hazır, görev bekliyorum."

start_agent "$PANE1" "$BACKEND_WD"  "/backend ${TASK_MSG:-$FALLBACK}"
start_agent "$PANE2" "$FRONTEND_WD" "/frontend ${TASK_MSG:-$FALLBACK}"
start_agent "$PANE3" "$QA_WD"       "/qa ${TASK_MSG:-$FALLBACK}"

tmux select-pane -t "$PANE0"

echo "✓ Workspace hazır — Backend, Frontend, QA aktif"
[ "$WORKSPACE_MODE" = "monorepo" ] && \
    echo "  Worktree temizliği için: bash .claude/scripts/workspace-cleanup.sh"
