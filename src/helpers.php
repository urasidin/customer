<?php
declare(strict_types=1);

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}

function flash(?string $message = null): ?string
{
    if ($message !== null) {
        $_SESSION['flash'] = $message;
        return null;
    }
    $msg = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $msg;
}

function csrf_token(): string
{
    return $_SESSION['csrf'] ??= bin2hex(random_bytes(32));
}

function csrf_field(): string
{
    return '<input type="hidden" name="_token" value="' . csrf_token() . '">';
}

function verify_csrf(): void
{
    if (!hash_equals(csrf_token(), (string) ($_POST['_token'] ?? ''))) {
        http_response_code(419);
        exit('Token CSRF tidak valid. Silakan muat ulang halaman.');
    }
}

function view(string $name, array $data = []): void
{
    extract($data);
    ob_start();
    require __DIR__ . '/../views/' . $name . '.php';
    $content = ob_get_clean();
    require __DIR__ . '/../views/layout.php';
}

/** Ambil & rapikan input form customer. */
function customer_input(): array
{
    $clean = fn(string $key): string => trim((string) ($_POST[$key] ?? ''));

    return [
        'name'    => $clean('name'),
        'email'   => strtolower($clean('email')),
        'phone'   => $clean('phone'),
        'address' => $clean('address'),
    ];
}

function validate_customer(array $d): array
{
    $errors = [];
    if ($d['name'] === '' || mb_strlen($d['name']) > 100) {
        $errors['name'] = 'Nama wajib diisi (maks. 100 karakter).';
    }
    if (!filter_var($d['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Format email tidak valid.';
    }
    if ($d['phone'] !== '' && !preg_match('/^[0-9+\-\s()]{6,30}$/', $d['phone'])) {
        $errors['phone'] = 'Nomor telepon tidak valid.';
    }
    return $errors;
}
