.PHONY: up down build shell migrate fresh logs test storage-link artisan composer npm prod-up prod-down

DC = docker compose --env-file backend/.env

## Geliştirme ortamını başlat
up:
	$(DC) up -d --build

## Servisleri durdur
down:
	$(DC) down

## Sadece imajları yeniden derle
build:
	$(DC) build --no-cache

## App container'ına shell aç
shell:
	$(DC) exec app bash

## Migration çalıştır
migrate:
	$(DC) exec app php artisan migrate

## Veritabanını sıfırla ve seedleri çalıştır
fresh:
	$(DC) exec app php artisan migrate:fresh --seed

## Logları izle
logs:
	$(DC) logs -f

## Testleri çalıştır (container içinde)
test:
	$(DC) exec app php artisan test --no-coverage

## Storage linki oluştur
storage-link:
	$(DC) exec app php artisan storage:link

## Artisan komutu çalıştır (örnek: make artisan CMD="route:list")
artisan:
	$(DC) exec app php artisan $(CMD)

## Composer komutu (örnek: make composer CMD="require package/name")
composer:
	$(DC) exec app composer $(CMD)

## npm komutu (örnek: make npm CMD="install")
npm:
	$(DC) exec app npm $(CMD)

## Production build
prod-up:
	$(DC) -f docker-compose.yml -f docker-compose.prod.yml up -d --build

## Production durdur
prod-down:
	$(DC) -f docker-compose.yml -f docker-compose.prod.yml down
