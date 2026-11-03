# Customer App

Aplikasi CRUD customer sederhana dengan PHP native tanpa framework + MySQL/MariaDB.
Butuh minimal PHP 8.2 (production memakai PHP 8.4).

## Struktur

```
config/   config.example.php (template), config.php (lokal, tidak di-commit)
database/ schema.sql
public/   index.php  <- document root web server
src/      Database, CustomerRepository, helpers
views/    template HTML
```

## Instalasi

1. Buat database & tabel: `mysql -u root -p < database/schema.sql`
2. Salin `config/config.example.php` menjadi `config/config.php`, isi kredensial database.
3. Jalankan lokal: `php -S localhost:8000 -t public` lalu buka http://localhost:8000

## Production

Arahkan document root web server ke folder `public/` (bukan root project), set `'debug' => false`.
