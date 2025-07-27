<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function index()
    {
        return redirect()->to('/auth/login');
    }
    
    public function login()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/books');
        }
        
        return view('auth/login');
    }
    
    public function authenticate()
    {
        $userModel = new UserModel();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        
        $user = $userModel->where('username', $username)->first();
        
        if ($user) {
            if (password_verify($password, $user['password'])) {
                $session_data = [
                    'user_id' => $user['id'],
                    'username' => $user['username'],
                    'nama_lengkap' => $user['nama_lengkap'],
                    'role' => $user['role'],
                    'logged_in' => TRUE
                ];
                
                session()->set($session_data);
                return redirect()->to('/books');
            } else {
                session()->setFlashdata('error', 'Password salah');
                return redirect()->to('/auth/login');
            }
        } else {
            session()->setFlashdata('error', 'Username tidak ditemukan');
            return redirect()->to('/auth/login');
        }
    }
    
    public function register()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/books');
        }
        
        return view('auth/register');
    }
    
    public function save()
    {
        $userModel = new UserModel();
        
        $rules = [
            'username' => 'required|min_length[4]|is_unique[users.username]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'matches[password]',
            'nama_lengkap' => 'required',
            'email' => 'required|valid_email|is_unique[users.email]',
        ];
        
        if ($this->validate($rules)) {
            $data = [
                'username' => $this->request->getPost('username'),
                'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'nama_lengkap' => $this->request->getPost('nama_lengkap'),
                'email' => $this->request->getPost('email'),
            ];
            
            $userModel->save($data);
            session()->setFlashdata('success', 'Registrasi berhasil, silakan login');
            return redirect()->to('/auth/login');
        } else {
            return view('auth/register', [
                'validation' => $this->validator
            ]);
        }
    }
    
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth/login');
    }
}