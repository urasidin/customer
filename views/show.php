<h1 class="h3 mb-3">Detail Customer</h1>

<div class="card">
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-3">ID</dt><dd class="col-sm-9"><?= (int) $customer['id'] ?></dd>
            <dt class="col-sm-3">Nama</dt><dd class="col-sm-9"><?= e($customer['name']) ?></dd>
            <dt class="col-sm-3">Email</dt><dd class="col-sm-9"><?= e($customer['email']) ?></dd>
            <dt class="col-sm-3">Telepon</dt><dd class="col-sm-9"><?= e($customer['phone']) ?: '-' ?></dd>
            <dt class="col-sm-3">Alamat</dt><dd class="col-sm-9"><?= nl2br(e($customer['address'])) ?: '-' ?></dd>
            <dt class="col-sm-3">Dibuat</dt><dd class="col-sm-9"><?= e($customer['created_at']) ?></dd>
            <dt class="col-sm-3">Diubah</dt><dd class="col-sm-9"><?= e($customer['updated_at']) ?></dd>
        </dl>
    </div>
    <div class="card-footer">
        <a class="btn btn-warning" href="index.php?action=edit&id=<?= (int) $customer['id'] ?>">Edit</a>
        <a class="btn btn-secondary" href="index.php">Kembali</a>
    </div>
</div>
