<?php

namespace App\Controllers;

use App\Models\BookModel;

class Books extends BaseController
{
    protected $bookModel;
    protected $session;
    
    public function __construct()
    {
        $this->bookModel = new BookModel();
        $this->session = session();
    }
    
    public function index()
    {
        // Cek apakah user sudah login
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/auth/login');
        }
        
        $keyword = $this->request->getGet('keyword');
        $page = $this->request->getGet('page') ?? 1;
        
        if ($keyword) {
            $books = $this->bookModel->search($keyword)->paginate(10, 'books');
        } else {
            $books = $this->bookModel->paginate(10, 'books');
        }
        
        $data = [
            'books' => $books,
            'pager' => $this->bookModel->pager,
            'page' => $page,
            'keyword' => $keyword
        ];
        
        return view('books/index', $data);
    }
    
    public function create()
    {
        // Cek apakah user sudah login
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/auth/login');
        }
        
        // Hanya admin yang boleh menambah buku
        if ($this->session->get('role') !== 'admin') {
            return redirect()->to('/books')->with('error', 'Hanya admin yang boleh menambah buku');
        }
        
        return view('books/create');
    }
    
    public function store()
    {
        // Cek apakah user sudah login
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/auth/login');
        }
        
        // Hanya admin yang boleh menambah buku
        if ($this->session->get('role') !== 'admin') {
            return redirect()->to('/books')->with('error', 'Hanya admin yang boleh menambah buku');
        }
        
        $rules = [
            'judul' => 'required',
            'penulis' => 'required',
            'penerbit' => 'required',
            'tahun_terbit' => 'required|numeric',
            'isbn' => 'required',
            'kategori' => 'required',
            'stok' => 'required|numeric',
            'cover' => 'uploaded[cover]|max_size[cover,1024]|is_image[cover]'
        ];
        
        if ($this->validate($rules)) {
            $coverFile = $this->request->getFile('cover');
            $coverName = $coverFile->getRandomName();
            $coverFile->move('uploads/covers', $coverName);
            
            $data = [
                'judul' => $this->request->getPost('judul'),
                'penulis' => $this->request->getPost('penulis'),
                'penerbit' => $this->request->getPost('penerbit'),
                'tahun_terbit' => $this->request->getPost('tahun_terbit'),
                'isbn' => $this->request->getPost('isbn'),
                'kategori' => $this->request->getPost('kategori'),
                'sinopsis' => $this->request->getPost('sinopsis'),
                'stok' => $this->request->getPost('stok'),
                'cover' => $coverName
            ];
            
            $this->bookModel->save($data);
            return redirect()->to('/books')->with('success', 'Buku berhasil ditambahkan');
        } else {
            return view('books/create', [
                'validation' => $this->validator
            ]);
        }
    }
    
    public function show($id = null)
    {
        // Cek apakah user sudah login
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/auth/login');
        }
        
        $data = [
            'book' => $this->bookModel->find($id)
        ];
        
        if ($data['book'] == null) {
            return redirect()->to('/books')->with('error', 'Buku tidak ditemukan');
        }
        
        return view('books/show', $data);
    }
    
    public function edit($id = null)
    {
        // Cek apakah user sudah login
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/auth/login');
        }
        
        // Hanya admin yang boleh mengedit buku
        if ($this->session->get('role') !== 'admin') {
            return redirect()->to('/books')->with('error', 'Hanya admin yang boleh mengedit buku');
        }
        
        $data = [
            'book' => $this->bookModel->find($id)
        ];
        
        if ($data['book'] == null) {
            return redirect()->to('/books')->with('error', 'Buku tidak ditemukan');
        }
        
        return view('books/edit', $data);
    }
    
    public function update($id = null)
    {
        // Cek apakah user sudah login
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/auth/login');
        }
        
        // Hanya admin yang boleh mengedit buku
        if ($this->session->get('role') !== 'admin') {
            return redirect()->to('/books')->with('error', 'Hanya admin yang boleh mengedit buku');
        }
        
        $rules = [
            'judul' => 'required',
            'penulis' => 'required',
            'penerbit' => 'required',
            'tahun_terbit' => 'required|numeric',
            'isbn' => 'required',
            'kategori' => 'required',
            'stok' => 'required|numeric',
        ];
        
        // Jika ada cover baru
        if ($this->request->getFile('cover')->isValid()) {
            $rules['cover'] = 'uploaded[cover]|max_size[cover,1024]|is_image[cover]';
        }
        
        if ($this->validate($rules)) {
            $data = [
                'judul' => $this->request->getPost('judul'),
                'penulis' => $this->request->getPost('penulis'),
                'penerbit' => $this->request->getPost('penerbit'),
                'tahun_terbit' => $this->request->getPost('tahun_terbit'),
                'isbn' => $this->request->getPost('isbn'),
                'kategori' => $this->request->getPost('kategori'),
                'sinopsis' => $this->request->getPost('sinopsis'),
                'stok' => $this->request->getPost('stok'),
            ];
            
            // Jika ada cover baru
            if ($this->request->getFile('cover')->isValid()) {
                $coverFile = $this->request->getFile('cover');
                $coverName = $coverFile->getRandomName();
                $coverFile->move('uploads/covers', $coverName);
                $data['cover'] = $coverName;
                
                // Hapus cover lama
                $oldBook = $this->bookModel->find($id);
                if ($oldBook['cover'] != 'default_cover.jpg') {
                    unlink('uploads/covers/' . $oldBook['cover']);
                }
            }
            
            $this->bookModel->update($id, $data);
            return redirect()->to('/books')->with('success', 'Buku berhasil diupdate');
        } else {
            return view('books/edit', [
                'validation' => $this->validator,
                'book' => $this->bookModel->find($id)
            ]);
        }
    }
    
    public function delete($id = null)
    {
        // Cek apakah user sudah login
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/auth/login');
        }
        
        // Hanya admin yang boleh menghapus buku
        if ($this->session->get('role') !== 'admin') {
            return redirect()->to('/books')->with('error', 'Hanya admin yang boleh menghapus buku');
        }
        
        $book = $this->bookModel->find($id);
        
        if ($book == null) {
            return redirect()->to('/books')->with('error', 'Buku tidak ditemukan');
        }
        
        // Hapus cover jika bukan default
        if ($book['cover'] != 'default_cover.jpg') {
            unlink('uploads/covers/' . $book['cover']);
        }
        
        $this->bookModel->delete($id);
        return redirect()->to('/books')->with('success', 'Buku berhasil dihapus');
    }
}