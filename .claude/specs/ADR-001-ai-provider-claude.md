# ADR-001: AI Sağlayıcısı Olarak Claude (claude-sonnet-4-6)

Tarih: 2026-05-06
Durum: Accepted

## Bağlam
Vision destekli bir LLM ile yemek fotoğrafından Türkçe tarif üretmemiz gerekiyor.

## Karar
`claude-sonnet-4-6` vision API kullanılacak, `RecipeAnalyzerInterface` arkasına alınacak.

## Yapı
```
App\Services\AI\
├── Contracts\RecipeAnalyzerInterface.php
├── Adapters\ClaudeRecipeAnalyzer.php
├── Adapters\FakeRecipeAnalyzer.php
└── DTOs\RecipeAnalysisResult.php
```

## Sonuçlar
- (+) Türkçe çıktı kalitesi yüksek, structured output güvenli
- (+) Adapter sayesinde sağlayıcı değişimi izole
- (-) Tek vendor'a operasyonel bağımlılık (API kotası, fiyat değişimi)

## Alternatifler
1. OpenAI GPT-4o Vision — Türkçe yemek bilgisi nispeten zayıf
2. Google Vision API — Sadece etiketleme, tarif üretmez; LLM ile birleştirmek karmaşık
