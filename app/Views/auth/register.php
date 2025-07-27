<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Register</h4>
            </div>
            <div class="card-body">
                <?= form_open('auth/save') ?>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('username') ? 'is-invalid' : '' ?>" id="username" name="username" value="<?= old('username') ?>" required>
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('username') : '' ?>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('nama_lengkap') ? 'is-invalid' : '' ?>" id="nama_lengkap" name="nama_lengkap" value="<?= old('nama_lengkap') ?>" required>
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('nama_lengkap') : '' ?>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control <?= isset($validation) && $validation->hasError('email') ? 'is-invalid' : '' ?>" id="email" name="email" value="<?= old('email') ?>" required>
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('email') : '' ?>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control <?= isset($validation) && $validation->hasError('password') ? 'is-invalid' : '' ?>" id="password" name="password" required>
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('password') : '' ?>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control <?= isset($validation) && $validation->hasError('confirm_password') ? 'is-invalid' : '' ?>" id="confirm_password" name="confirm_password" required>
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('confirm_password') : '' ?>
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Register</button>
                    </div>
                <?= form_close() ?>
                <hr>
                <p class="text-center">Sudah punya akun? <a href="<?= base_url('/auth/login') ?>">Login</a></p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>