<?php

namespace App\Models;

use CodeIgniter\Model;

class BookModel extends Model
{
    protected $table = 'books';
    protected $primaryKey = 'id';
    protected $allowedFields = ['judul', 'penulis', 'penerbit', 'tahun_terbit', 'isbn', 'kategori', 'sinopsis', 'stok', 'cover'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    public function search($keyword)
    {
        return $this->like('judul', $keyword)
                    ->orLike('penulis', $keyword)
                    ->orLike('penerbit', $keyword)
                    ->orLike('kategori', $keyword);
    }
}