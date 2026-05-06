# GitHub Workflow

Görev netleştirildikten ve mimari karara varıldıktan sonra GitHub iş akışını yönetir.

$ARGUMENTS

---

## İzin Verilen İşlemler

| İşlem | Araç |
|---|---|
| Issue aç | `create_issue` |
| Issue'ya geliştirme notu ekle | `add_issue_comment` |
| Issue'yu kapat (DoD tamamsa) | `update_issue` → state: closed |
| Branch aç | `create_branch` |
| Dosya commit et | `create_or_update_file` |
| PR aç | `create_pull_request` |
| PR'a bilgi ekle | `add_pull_request_review_comment` |

## YASAK İşlemler

**MERGE ETMEYECEKSİN — hiçbir koşulda.**
- `merge_pull_request` çağrısı yapma
- PR'ı merged olarak işaretleme
- Branch'leri birleştirme

Merge kararı her zaman kullanıcıya aittir.

---

## Zorunlu Sıra — Bu Sıra Hiçbir Zaman Atlanamaz

```
1. Issue aç          → numara al (#N)
2. Spec oluştur      → .claude/specs/ADR-N-kisa-aciklama.md
3. Branch aç         → feature/N-kisa-aciklama
4. Implementasyon    → QA → Backend → Frontend → DevOps
5. PR aç             → [#N] feat/fix: kisa-aciklama
```

**Kural:** Issue numarası alınmadan branch açılmaz, branch açılmadan implementasyon başlamaz.

---

## İsimlendirme Formatı

| Artefakt | Format | Örnek |
|---|---|---|
| Issue | `#N` | `#42` |
| Spec/ADR | `ADR-N-kisa-aciklama` | `ADR-42-user-soft-delete` |
| Branch | `feature/N-kisa-aciklama` | `feature/42-user-soft-delete` |
| PR başlığı | `[#N] feat: kisa aciklama` | `[#42] feat: user soft delete` |

---

## Standart Görev Akışı

### 1. Issue Aç
Görevi GitHub issue olarak belgele — implementasyona başlamadan önce:
```
Başlık: [kısa, net görev adı]
Body:
  ## Amaç
  [ne yapılacak]

  ## Kabul Kriterleri
  - [ ] [madde 1]
  - [ ] [madde 2]

  ## Teknik Notlar
  [mimari karar, ilgili dosyalar, dikkat edilecekler]
```

### 2. Spec Dosyası Oluştur
`.claude/specs/ADR-<N>-<kisa-aciklama>.md` dosyasını oluştur:
```
# ADR-N: [Görev Adı]

## Karar
[ne yapılacak]

## Gerekçe
[neden bu yol seçildi]

## Etkilenen Bileşenler
[dosyalar, katmanlar]

## Kabul Kriterleri
- [ ] ...
```

### 3. Branch Aç
```
feature/<N>-<kisa-aciklama>
fix/<N>-<kisa-aciklama>
```
Örnek: `feature/42-user-soft-delete`

### 4. Geliştirme Sürecinde Issue'yu Güncelle
Her önemli adımda issue'ya comment ekle:
```
## ✅ [adım adı] tamamlandı
- Migration oluşturuldu: `2026_xx_xx_create_user_logs_table`
- Endpoint eklendi: `GET /api/v1/user-logs`
- Test: 4 senaryo yeşil
```

### 5. PR Aç
```
Başlık: [#N] feat: kisa-aciklama
Body:
  Closes #<N>

  ## Değişiklikler
  - [madde]

  ## Test
  - [ ] Testler yeşil
  - [ ] composer audit temiz
  - [ ] PHPStan hatasız
```

### 6. DoD Tamamsa Issue'yu Kapat
Tüm kabul kriterleri karşılandığında issue'yu `closed` olarak işaretle.
**PR'ı merge etme — bu adım kullanıcıya aittir.**

---

## Token Gereksinimleri

`GITHUB_PERSONAL_ACCESS_TOKEN` için minimum scope:
- `repo` — repository, issue, PR, branch erişimi
- `read:org` — organizasyon bilgisi (gerekiyorsa)
