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

## Standart Görev Akışı

### 1. Issue Aç
Görevi GitHub issue olarak belgele:
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

### 2. Branch Aç
```
feature/<issue-no>-<kısa-açıklama>
fix/<issue-no>-<kısa-açıklama>
```
Örnek: `feature/42-user-soft-delete-log`

### 3. Geliştirme Sürecinde Issue'yu Güncelle
Her önemli adımda issue'ya comment ekle:
```
## ✅ [adım adı] tamamlandı
- Migration oluşturuldu: `2026_xx_xx_create_user_logs_table`
- Endpoint eklendi: `GET /api/v1/user-logs`
- Test: 4 senaryo yeşil
```

### 4. PR Aç
```
Başlık: [#issue-no] [görev adı]
Body:
  Closes #<issue-no>

  ## Değişiklikler
  - [madde]

  ## Test
  - [ ] Behat / Playwright testleri yeşil
  - [ ] composer audit temiz
  - [ ] PHPStan hatasız
```

### 5. DoD Tamamsa Issue'yu Kapat
Tüm kabul kriterleri karşılandığında issue'yu `closed` olarak işaretle.
**PR'ı merge etme — bu adım kullanıcıya aittir.**

---

## Token Gereksinimleri

`GITHUB_PERSONAL_ACCESS_TOKEN` için minimum scope:
- `repo` — repository, issue, PR, branch erişimi
- `read:org` — organizasyon bilgisi (gerekiyorsa)
