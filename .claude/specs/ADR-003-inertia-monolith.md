# ADR-003: Inertia.js + Sanctum Monolith

Tarih: 2026-05-06
Durum: Accepted

## Bağlam
Tek geliştirici/küçük ekip, hızlı iterasyon, web-only ürün.

## Karar
Vue 3 + Inertia + Sanctum cookie auth. Tek repo (Laravel monolith), tek deploy.
Frontend dosyaları `backend/resources/js/` altında yaşar.

## Sonuçlar
- (+) CORS/token refresh derdi yok, CSRF Laravel'de
- (+) Route ve auth tek yerde, hızlı iterasyon
- (-) 3rd-party/mobile API client çıkarsa ek API katmanı şart

## Alternatifler
1. Ayrı SPA + REST API — overengineering, mobile gereksinimi yok
2. Livewire — Vue ekosistemi tercih edildi
