<h1 class="h3 mb-3"><?= e($title) ?></h1>

<div class="card">
    <div class="card-body">
        <form method="post" action="<?= e($formAction) ?>" novalidate>
            <?= csrf_field() ?>

            <?php
            $field = function (string $name, string $label, string $type = 'text') use ($data, $errors) {
                $invalid = isset($errors[$name]) ? ' is-invalid' : '';
                echo '<div class="mb-3"><label class="form-label" for="' . $name . '">' . e($label) . '</label>';
                if ($type === 'textarea') {
                    echo '<textarea class="form-control' . $invalid . '" id="' . $name . '" name="' . $name . '" rows="3">' . e($data[$name] ?? '') . '</textarea>';
                } else {
                    echo '<input class="form-control' . $invalid . '" type="' . $type . '" id="' . $name . '" name="' . $name . '" value="' . e($data[$name] ?? '') . '">';
                }
                if ($invalid) {
                    echo '<div class="invalid-feedback">' . e($errors[$name]) . '</div>';
                }
                echo '</div>';
            };
            $field('name', 'Nama *');
            $field('email', 'Email *', 'email');
            $field('phone', 'Telepon', 'tel');
            $field('address', 'Alamat', 'textarea');
            ?>

            <button class="btn btn-primary">Simpan</button>
            <a class="btn btn-secondary" href="index.php">Batal</a>
        </form>
    </div>
</div>
