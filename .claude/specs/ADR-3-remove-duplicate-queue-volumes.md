# ADR-3: docker-compose.prod.yml queue servisindeki gereksiz volumes kaldır

## Karar
`queue` servisindeki boş `volumes: []` tanımı (satır 11) kaldırılacak. Supervisor config mount'u içeren asıl `volumes` tanımı korunacak.

## Gerekçe
YAML'da aynı anahtarın iki kez geçmesi okunabilirliği bozar. Son tanım geçerli olduğundan işlevsel bir sorun yoktur; ancak bu durum bakım riskidir.

## Etkilenen Bileşenler
- `docker-compose.prod.yml` — `queue` servisi

## Kabul Kriterleri
- [ ] `queue` servisinde tek `volumes` anahtarı var
- [ ] `supervisord.conf:ro` mount'u korunuyor
- [ ] Dosya geçerli YAML
