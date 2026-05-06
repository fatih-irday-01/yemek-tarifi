#!/bin/bash
# workspace-cleanup.sh — Monorepo worktree'lerini temizler
#
# Kullanım:
#   bash .claude/scripts/workspace-cleanup.sh          # worktree'leri sil
#   bash .claude/scripts/workspace-cleanup.sh --merge  # önce merge, sonra sil

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(cd "$SCRIPT_DIR/../.." && pwd)"
CONF="$PROJECT_ROOT/.claude/workspace.conf"

WORKTREE_DIR=".worktrees"
[ -f "$CONF" ] && source "$CONF"

MERGE_FLAG="${1:-}"
WT_PATH="$PROJECT_ROOT/$WORKTREE_DIR"

if [ ! -d "$WT_PATH" ]; then
    echo "Temizlenecek worktree bulunamadı: $WT_PATH"
    exit 0
fi

# Mevcut worktree'leri listele
WORKTREES=$(git -C "$PROJECT_ROOT" worktree list --porcelain \
    | grep "worktree " | grep "$WORKTREE_DIR" | awk '{print $2}' || true)

if [ -z "$WORKTREES" ]; then
    echo "Aktif worktree yok."
    exit 0
fi

echo "Bulunan worktree'ler:"
echo "$WORKTREES" | while read -r wt; do echo "  - $wt"; done
echo ""

# Merge seçeneği
if [ "$MERGE_FLAG" = "--merge" ]; then
    MAIN_BRANCH=$(git -C "$PROJECT_ROOT" symbolic-ref --short HEAD)
    echo "main branch: $MAIN_BRANCH"
    echo ""

    echo "$WORKTREES" | while read -r wt; do
        WD_BRANCH=$(git -C "$wt" rev-parse --abbrev-ref HEAD 2>/dev/null || true)
        if [ -n "$WD_BRANCH" ]; then
            echo "Merge ediliyor: $WD_BRANCH → $MAIN_BRANCH"
            git -C "$PROJECT_ROOT" merge --no-ff "$WD_BRANCH" \
                -m "Merge workspace branch: $WD_BRANCH" || {
                echo "HATA: $WD_BRANCH merge başarısız — manuel çakışma çözümü gerekiyor"
            }
        fi
    done
    echo ""
fi

# Worktree'leri kaldır
echo "$WORKTREES" | while read -r wt; do
    git -C "$PROJECT_ROOT" worktree remove --force "$wt" 2>/dev/null || true
    echo "Silindi: $wt"
done

git -C "$PROJECT_ROOT" worktree prune
rm -rf "$WT_PATH" 2>/dev/null || true

echo ""
echo "✓ Workspace temizlendi."
