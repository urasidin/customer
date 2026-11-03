<?php
declare(strict_types=1);

use App\CustomerRepository;
use App\Database;

session_start(['cookie_httponly' => true, 'cookie_samesite' => 'Lax']);

$configFile = __DIR__ . '/../config/config.php';
if (!is_file($configFile)) {
    http_response_code(500);
    exit('config/config.php belum ada. Salin dari config/config.example.php.');
}
$config = require $configFile;

ini_set('display_errors', $config['debug'] ? '1' : '0');
error_reporting(E_ALL);

require __DIR__ . '/../src/helpers.php';
require __DIR__ . '/../src/Database.php';
require __DIR__ . '/../src/CustomerRepository.php';

try {
    $repo = new CustomerRepository(Database::connect($config['db']));
} catch (PDOException $e) {
    http_response_code(500);
    exit($config['debug'] ? 'Koneksi database gagal: ' . e($e->getMessage()) : 'Koneksi database gagal.');
}

$action = $_GET['action'] ?? 'index';
$id     = (int) ($_GET['id'] ?? 0);
$isPost = $_SERVER['REQUEST_METHOD'] === 'POST';

if ($isPost) {
    verify_csrf();
}

match ($action) {
    // READ: daftar + pencarian + paginasi
    'index' => (function () use ($repo) {
        $search  = trim((string) ($_GET['q'] ?? ''));
        $page    = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 10;
        $result  = $repo->paginate($search, $page, $perPage);
        view('index', [
            'customers' => $result['items'],
            'total'     => $result['total'],
            'pages'     => max(1, (int) ceil($result['total'] / $perPage)),
            'page'      => $page,
            'search'    => $search,
        ]);
    })(),

    // CREATE
    'create' => (function () use ($repo, $isPost) {
        $data = ['name' => '', 'email' => '', 'phone' => '', 'address' => ''];
        $errors = [];
        if ($isPost) {
            $data = customer_input();
            $errors = validate_customer($data);
            if (!isset($errors['email']) && $repo->emailExists($data['email'])) {
                $errors['email'] = 'Email sudah terdaftar.';
            }
            if (!$errors) {
                $repo->create($data);
                flash('Customer berhasil ditambahkan.');
                redirect('index.php');
            }
        }
        view('form', ['title' => 'Tambah Customer', 'data' => $data, 'errors' => $errors, 'formAction' => 'index.php?action=create']);
    })(),

    // READ detail
    'show' => (function () use ($repo, $id) {
        $customer = $repo->find($id) ?? notFound();
        view('show', ['customer' => $customer]);
    })(),

    // UPDATE
    'edit' => (function () use ($repo, $id, $isPost) {
        $data = $repo->find($id) ?? notFound();
        $errors = [];
        if ($isPost) {
            $data = customer_input();
            $errors = validate_customer($data);
            if (!isset($errors['email']) && $repo->emailExists($data['email'], $id)) {
                $errors['email'] = 'Email sudah dipakai customer lain.';
            }
            if (!$errors) {
                $repo->update($id, $data);
                flash('Customer berhasil diperbarui.');
                redirect('index.php');
            }
        }
        view('form', ['title' => 'Edit Customer', 'data' => $data, 'errors' => $errors, 'formAction' => "index.php?action=edit&id=$id"]);
    })(),

    // DELETE (hanya via POST)
    'delete' => (function () use ($repo, $id, $isPost) {
        if (!$isPost) {
            http_response_code(405);
            exit('Method not allowed');
        }
        $repo->delete($id);
        flash('Customer berhasil dihapus.');
        redirect('index.php');
    })(),

    default => notFound(),
};

function notFound(): never
{
    http_response_code(404);
    exit('Halaman tidak ditemukan.');
}
