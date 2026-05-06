# Security Review

Belirtilen dosya veya özellik için güvenlik taraması yapar.

$ARGUMENTS

---

`.claude/agents/security-reviewer.md` ve `.claude/skills/laravel-security/SKILL.md` dosyalarını oku, ardından şu görevi uygula:

**Taranacak hedef:** $ARGUMENTS (belirtilmemişse son değiştirilen dosyalar)

Tarama adımları:
1. `composer audit` çalıştır
2. OWASP Top 10 kontrolü yap
3. Kritik pattern'ları tara (hardcoded secret, raw SQL, doğrulanmamış input, eksik auth)
4. Laravel güvenlik checklist'ini uygula

Çıktı formatı:
```
🔒 Güvenlik Taraması: [hedef]

Bulunanlar:
- [SEVİYE] [açıklama] → [dosya:satır] → Düzeltme: [öneri]

Onaylananlar:
- [kontrol]: ✅

Sonuç: GEÇTİ / BLOKLANMALI
```
