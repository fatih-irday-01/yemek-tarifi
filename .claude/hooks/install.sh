#!/usr/bin/env bash
# Git hook'larını aktif et
# Çalıştırma: bash .claude/hooks/install.sh

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
HOOKS_DIR="$SCRIPT_DIR"

git config core.hooksPath "$HOOKS_DIR"

chmod +x "$HOOKS_DIR/pre-commit"
chmod +x "$HOOKS_DIR/pre-push"

echo "✅ Git hooks aktif edildi: $HOOKS_DIR"
echo ""
echo "Aktif hook'lar:"
echo "  • pre-commit → claude ile code review (bilgilendirme)"
echo "  • pre-push   → secret/key/env güvenlik taraması (bloklayıcı)"
