<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="card shadow">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Tambah Buku</h4>
    </div>
    <div class="card-body">
        <?= form_open_multipart('books/store') ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul Buku</label>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('judul') ? 'is-invalid' : '' ?>" id="judul" name="judul" value="<?= old('judul') ?>" required>
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('judul') : '' ?>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="penulis" class="form-label">Penulis</label>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('penulis') ? 'is-invalid' : '' ?>" id="penulis" name="penulis" value="<?= old('penulis') ?>" required>
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('penulis') : '' ?>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="penerbit" class="form-label">Penerbit</label>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('penerbit') ? 'is-invalid' : '' ?>" id="penerbit" name="penerbit" value="<?= old('penerbit') ?>" required>
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('penerbit') : '' ?>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                        <input type="number" class="form-control <?= isset($validation) && $validation->hasError('tahun_terbit') ? 'is-invalid' : '' ?>" id="tahun_terbit" name="tahun_terbit" value="<?= old('tahun_terbit') ?>" required>
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('tahun_terbit') : '' ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="isbn" class="form-label">ISBN</label>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('isbn') ? 'is-invalid' : '' ?>" id="isbn" name="isbn" value="<?= old('isbn') ?>" required>
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('isbn') : '' ?>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="kategori" class="form-label">Kategori</label>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('kategori') ? 'is-invalid' : '' ?>" id="kategori" name="kategori" value="<?= old('kategori') ?>" required>
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('kategori') : '' ?>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="stok" class="form-label">Stok</label>
                        <input type="number" class="form-control <?= isset($validation) && $validation->hasError('stok') ? 'is-invalid' : '' ?>" id="stok" name="stok" value="<?= old('stok') ?? 1 ?>" required>
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('stok') : '' ?>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="cover" class="form-label">Cover Buku</label>
                        <input type="file" class="form-control <?= isset($validation) && $validation->hasError('cover') ? 'is-invalid' : '' ?>" id="cover" name="cover" accept="image/*" required>
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('cover') : '' ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-12">
                    <div class="mb-3">
                        <label for="sinopsis" class="form-label">Sinopsis</label>
                        <textarea class="form-control" id="sinopsis" name="sinopsis" rows="5"><?= old('sinopsis') ?></textarea>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-between mt-3">
                <a href="<?= base_url('books') ?>" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Buku</button>
            </div>
        <?= form_close() ?>
    </div>
</div>
<?= $this->endSection() ?>