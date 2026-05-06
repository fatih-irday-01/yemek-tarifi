# ADR-004: Tek Tablolu Şema (recipe_analyses)

Tarih: 2026-05-06
Durum: Accepted

## Bağlam
1 analiz = 1 fotoğraf = 1 tarif. Şu an arama/filtreleme gereksinimi yok.

## Karar
`recipe_analyses` tablosu fotoğraf metadata + JSON tarif içerecek.
Ayrı `photos` veya `ingredients` tabloları olmayacak.

## Şema
```sql
recipe_analyses
├── id                BIGINT PK
├── user_id           FK → users.id (cascade)
├── photo_path        VARCHAR(500)
├── photo_disk        VARCHAR(50)        -- 'public' | 's3'
├── status            ENUM('pending','processing','completed','failed')
├── food_name         VARCHAR(255) NULL
├── recipe            JSON NULL          -- {ingredients:[], steps:[], cookingTime, servings}
├── error_message     TEXT NULL
├── ai_model          VARCHAR(50) NULL   -- 'claude-sonnet-4-6'
├── tokens_used       INT NULL
├── created_at        TIMESTAMP
└── updated_at        TIMESTAMP

INDEX idx_user_created (user_id, created_at DESC)
INDEX idx_status (status)
```

## Sonuçlar
- (+) Basit query, basit migration, daha az JOIN
- (+) JSON ile tarif yapısı esnek
- (-) Malzeme bazlı arama gerekirse refactor şart

## Alternatifler
1. photos + recipes ayrı tabloları — 1:1 ilişkide YAGNI
2. Tam normalize (recipe_ingredients, recipe_steps) — şu an gereksiz overhead
