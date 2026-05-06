# Son Oturum — 2026-05-06

## Tamamlanan Görevler
1. Laravel 13 + Inertia + Vue 3 monorepo + AI tarif analizi
2. Docker kurulumu: `make up` çalışıyor — HTTP 200 ✅
3. Nav menüsü: "Yeni Tarif" + "Geçmişim" linkleri eklendi (desktop + mobil) ✅
4. AI servisi Groq + Llama 3.2 Vision'a geçirildi (Laravel AI SDK) ✅

## Navigasyon Yapısı
- Logo → `dashboard`
- Nav: **Yeni Tarif** → `recipes.create` | **Geçmişim** → `history`
- Dropdown: Profile | Log Out
- Dashboard sayfası: iki kart ile hızlı erişim

## Docker Mimarisi
- `app`: php:8.4-fpm-alpine + install-php-extensions + entrypoint.sh
- `nginx`: port 8080 | `mysql`: 8.0 | `redis`: 7 (şifreli) | `queue`: worker
- Vendor: named volume, container içinde composer install
- Frontend: host'ta `npm run build` → volume üzerinden serve

## AI Servisi (güncel)
- Provider: **Groq** — model: `llama-3.2-90b-vision-preview`
- SDK: `laravel/ai` (Laravel 13 resmi)
- Agent: `App\Ai\Agents\RecipeAnalyzerAgent` (structured output)
- Adapter: `App\Services\AI\Adapters\GroqRecipeAnalyzer`
- Interface: `RecipeAnalyzerInterface::analyze(string $disk, string $path)` — base64 yok

## Önemli Kararlar
- SESSION_ENCRYPT=true, ayrı DB_ROOT_PASSWORD, Redis requirepass
- `resources/js/bootstrap.js` manuel oluşturuldu
- `install-php-extensions` ile binary download (pecl değil)
- `photo_disk` kolonu `RecipeAnalysis::$fillable` ve controller `create()`'e eklendi
- Ollama production config'den çıkarıldı (SSRF riski)

## Devam Eden / Bekleyen
- `GROQ_API_KEY` `.env` dosyasına girilmeli (şu an boş)
- Docker ortamında `php artisan migrate` çalıştırılmalı (agent_conversations tablosu)
- Production deploy: `docker-compose.prod.yml`
- PR #6 merge bekliyor (feature/5-auto-load-github-token)

## Son Tamamlanan (2026-05-06)
- `UserPromptSubmit` hook eklendi: `backend/.env`'den GITHUB_PERSONAL_ACCESS_TOKEN otomatik okunuyor
- `~/.zshrc` güncellendi: MCP server'lar için shell-level export
- GitHub token artık manuel export gerektirmiyor
