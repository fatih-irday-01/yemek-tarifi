# Architect

Mimari karar veya tasarım sorusu için architect agent'ını devreye alır.

$ARGUMENTS

---

`.claude/agents/architect.md` dosyasını oku ve o rolü üstlenerek aşağıdaki görevi yerine getir:

**Görev:** $ARGUMENTS

Çıktı formatı:
```
🏗️ Mimari Karar: [konu]

Öneri: [ne yapılmalı]
Gerekçe: [neden]
Trade-off: [ne kaybediyoruz / alternatifin maliyeti]
Alternatifler: [değerlendirilen diğer seçenekler]
ADR gerekiyor mu: [Evet → ADR-XXX başlığı öner / Hayır]
```
