# ADR-8: Landing Page UI Geliştirmesi

## Karar
`/` rotasına modern bir landing page eklenir. Giriş yapmış kullanıcılar `/dashboard`'a yönlendirilir.

## Gerekçe
Mevcut `Welcome.vue` Laravel'in default template'i — proje kimliğiyle uyumsuz. Yeni renk paleti ve uygulama odaklı içerikle değiştirilecek.

## Renk Paleti
- Primary: `#2D6A4F`
- Accent: `#95D5B2`
- BG: `#FFFFFF`

## Etkilenen Bileşenler
- `backend/resources/js/Pages/Welcome.vue` — komple yeniden yaz
- `backend/tailwind.config.js` — `primary` ve `accent` token ekle
- `backend/routes/web.php` — `/` route'una auth redirect ekle

## Kabul Kriterleri
- [ ] `/` rotasına girildiğinde landing page görüntülenir
- [ ] Giriş yapmış kullanıcı `/` → `/dashboard` redirect edilir
- [ ] "Giriş Yap" butonu login sayfasına yönlendirir
- [ ] "Kayıt ol" butonu register sayfasına yönlendirir
- [ ] Renk paleti doğru uygulanmış
- [ ] Responsive tasarım (mobile + desktop)
