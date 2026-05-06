---
name: GitHub Workflow Sırası
description: Issue önce açılır, sonra spec ve branch, implementasyon en sonda; isimlendirme formatı zorunlu
type: feedback
---

Görev onaylandıktan sonra sıra şöyle olmalı — bu sıra hiçbir zaman atlanamaz:

1. **GitHub issue aç** → numara al (örn. #2)
2. **Spec/ADR dosyası oluştur** → `.claude/specs/ADR-2-ornek-task.md`
3. **Branch aç** → `feature/2-ornek-task`
4. **Implementasyon** (backend, frontend, QA, DevOps)
5. **PR aç** → `[#2] feat: ornek task`

**İsimlendirme formatı:**
- Issue: `#N`
- Spec: `ADR-N-ornek-task`
- Branch: `feature/N-ornek-task`
- PR başlığı: `[#N] feat: ornek task`

**Why:** Önce kod yazılıp sonra issue açılınca traceability bozuluyor. Issue numarası branch ve spec adını belirler; önce kod varsa bu bağlantı kurulamaz.

**How to apply:** Kullanıcı onayı gelir gelmez ilk iş GitHub issue açmak. Issue numarası alınmadan branch açılmaz, implementasyon başlamaz.
