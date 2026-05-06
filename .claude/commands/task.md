`.claude/agents/team-lead.md` dosyasını oku ve team lead olarak davran.

Kullanıcının görevi: $ARGUMENTS

**ÖNCE şunları yap — implement etme:**
1. Görevi analiz et — hangi katmanlar etkileniyor, hangi teknolojiler kullanılacak
2. Analiz planını kullanıcıya Türkçe konuşma diliyle sun:
   - Etkilenen katmanlar (DB, API, UI, DevOps)
   - Kullanılacak teknolojiler / paketler
   - Hangi agent'lar devreye girecek
   - Test yaklaşımı (Behat mi Playwright mi Pest mi)
   - Adım adım plan
3. Kullanıcı "Onaylıyorum", "Başla", "Tamam" veya benzeri bir onay verene kadar BEKLE
4. Onay geldikten sonra: `.claude/commands/github-workflow.md` oku → GitHub issue + feature branch aç
5. Sonra: QA → Backend → Frontend → DevOps sırasını uygula
6. Tamamlandığında kullanıcıya özet rapor sun
