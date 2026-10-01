# ai-solution
## Docker (SQLite)

Project ini menyediakan setup Docker production sederhana dengan Laravel + FrankenPHP dan SQLite persistent. Tidak ada database container tambahan; database dan file upload disimpan di Docker volume.

### Menjalankan secara lokal

```bash
cp .env.docker.example .env.docker
```

Di PowerShell, gunakan:

```powershell
Copy-Item .env.docker.example .env.docker
```

Isi `APP_KEY` pada `.env.docker`, lalu jalankan:

```bash
docker compose build
docker compose up -d
docker compose exec app php artisan migrate --force
```

Aplikasi tersedia di `http://localhost:8082`.

Seeder tidak dijalankan otomatis. Jalankan hanya jika data awal memang diperlukan:

```bash
docker compose exec app php artisan db:seed --force
```

Database berada di volume `ai_solution_sqlite` dan upload berada di volume `ai_solution_storage`. Jangan gunakan `docker compose down -v` kecuali memang ingin menghapus data tersebut.
