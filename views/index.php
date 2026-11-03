<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h1 class="h3 m-0">Daftar Customer <small class="text-muted fs-6">(<?= $total ?>)</small></h1>
    <div class="d-flex gap-2">
        <form class="d-flex gap-2" method="get">
            <input class="form-control" type="search" name="q" value="<?= e($search) ?>" placeholder="Cari nama / email / telp">
            <button class="btn btn-outline-secondary">Cari</button>
        </form>
        <a class="btn btn-primary text-nowrap" href="index.php?action=create">+ Tambah</a>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
            <tr>
                <th>#</th><th>Nama</th><th>Email</th><th>Telepon</th><th>Alamat</th><th class="text-end">Aksi</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!$customers): ?>
                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data.</td></tr>
            <?php endif ?>
            <?php foreach ($customers as $c): ?>
                <tr>
                    <td><?= (int) $c['id'] ?></td>
                    <td><?= e($c['name']) ?></td>
                    <td><?= e($c['email']) ?></td>
                    <td><?= e($c['phone']) ?></td>
                    <td class="text-truncate" style="max-width: 250px" title="<?= e($c['address']) ?>"><?= e($c['address']) ?: '-' ?></td>
                    <td class="text-end text-nowrap">
                        <a class="btn btn-sm btn-outline-info" href="index.php?action=show&id=<?= (int) $c['id'] ?>">Detail</a>
                        <a class="btn btn-sm btn-outline-warning" href="index.php?action=edit&id=<?= (int) $c['id'] ?>">Edit</a>
                        <form class="d-inline" method="post" action="index.php?action=delete&id=<?= (int) $c['id'] ?>"
                              onsubmit="return confirm('Hapus customer <?= e(addslashes($c['name'])) ?>?')">
                            <?= csrf_field() ?>
                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>

<?php if ($pages > 1): ?>
<nav class="mt-3">
    <ul class="pagination">
        <?php for ($i = 1; $i <= $pages; $i++): ?>
            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                <a class="page-link" href="?<?= e(http_build_query(['q' => $search, 'page' => $i])) ?>"><?= $i ?></a>
            </li>
        <?php endfor ?>
    </ul>
</nav>
<?php endif ?>
