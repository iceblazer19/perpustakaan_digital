<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-md-8">
        <h2>Daftar Buku</h2>
    </div>
    <div class="col-md-4">
        <form action="<?= base_url('books') ?>" method="get">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Cari buku..." name="keyword" value="<?= $keyword ?? '' ?>">
                <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Cari</button>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <?php if (empty($books)): ?>
        <div class="col-12 text-center">
            <div class="alert alert-info">
                Tidak ada buku yang tersedia.
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($books as $book): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="<?= base_url('uploads/covers/' . $book['cover']) ?>" class="card-img-top book-cover" alt="<?= $book['judul'] ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= $book['judul'] ?></h5>
                        <p class="card-text text-muted"><?= $book['penulis'] ?></p>
                        <p class="card-text">
                            <span class="badge bg-primary"><?= $book['kategori'] ?></span>
                            <span class="badge bg-secondary"><?= $book['tahun_terbit'] ?></span>
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">Stok: <?= $book['stok'] ?></small>
                            <a href="<?= base_url('books/' . $book['id']) ?>" class="btn btn-sm btn-outline-primary">Detail</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Pagination -->
<div class="d-flex justify-content-center mt-4">
    <?= $pager->links('books', 'bootstrap_pagination') ?>
</div>

<?= $this->endSection() ?>