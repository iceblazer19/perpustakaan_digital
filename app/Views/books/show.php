<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="card shadow">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <img src="<?= base_url('uploads/covers/' . $book['cover']) ?>" class="img-fluid rounded" alt="<?= $book['judul'] ?>">
            </div>
            <div class="col-md-8">
                <h2><?= $book['judul'] ?></h2>
                <hr>
                <div class="mb-3">
                    <strong>Penulis:</strong> <?= $book['penulis'] ?>
                </div>
                <div class="mb-3">
                    <strong>Penerbit:</strong> <?= $book['penerbit'] ?>
                </div>
                <div class="mb-3">
                    <strong>Tahun Terbit:</strong> <?= $book['tahun_terbit'] ?>
                </div>
                <div class="mb-3">
                    <strong>ISBN:</strong> <?= $book['isbn'] ?>
                </div>
                <div class="mb-3">
                    <strong>Kategori:</strong> <?= $book['kategori'] ?>
                </div>
                <div class="mb-3">
                    <strong>Stok:</strong> <?= $book['stok'] ?>
                </div>
                <hr>
                <div class="mb-3">
                    <strong>Sinopsis:</strong>
                    <p><?= nl2br($book['sinopsis']) ?></p>
                </div>
                
                <?php if (session()->get('role') == 'admin'): ?>
                    <div class="mt-4">
                        <a href="<?= base_url('books/' . $book['id'] . '/edit') ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Edit</a>
                        <a href="#" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="fas fa-trash"></i> Hapus</a>
                    </div>
                <?php endif; ?>
                
                <a href="<?= base_url('books') ?>" class="btn btn-secondary mt-3"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus buku "<?= $book['judul'] ?>"?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a href="<?= base_url('books/' . $book['id'] . '/delete') ?>" class="btn btn-danger">Hapus</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>