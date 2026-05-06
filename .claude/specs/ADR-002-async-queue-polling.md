# ADR-002: Asenkron Analiz — Queue Job + Polling

Tarih: 2026-05-06
Durum: Accepted

## Bağlam
AI analizi 5-20 sn sürüyor. Senkron HTTP request'te tutmak timeout/UX sorunlarına yol açar.

## Karar
`AnalyzeRecipePhotoJob` ile queue'ya alınacak. Frontend 2 sn'de bir status'u poll edecek.

## Akış
```
POST /recipes/analyze (foto upload)
  → recipe_analyses kaydı (status=pending)
  → AnalyzeRecipePhotoJob dispatch
  → 202 Accepted + analysis_id dön
Frontend: 2sn'de bir GET /api/analyses/{id} → status=completed olunca tarifi göster
```

## Sonuçlar
- (+) HTTP worker'ları serbest, scale-friendly
- (+) Status alanı varsa Pusher/Reverb'e geçmek ileride kolay
- (-) Polling, WebSocket'e göre fazla request üretir (kabul edilebilir MVP)

## Alternatifler
1. Senkron — kötü UX, worker tıkanması, timeout riski
2. Pusher/Reverb baştan — ek altyapı, MVP için aşırı
