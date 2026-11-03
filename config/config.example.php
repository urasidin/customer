<?php
// Salin file ini menjadi config.php lalu isi sesuai environment (lokal / production).
// config.php TIDAK ikut di-commit ke GitHub.
return [
    'db' => [
        'host'     => 'localhost',
        'port'     => 3306,
        'database' => 'customer',
        'username' => 'root',
        'password' => '',
    ],
    'debug' => false, // true hanya di lokal
];
