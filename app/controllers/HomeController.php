<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Middleware\AuthMiddleware;

class HomeController extends Controller
{
    public function __construct()
    {
        AuthMiddleware::check();
        AuthMiddleware::checkAdmin();
        AuthMiddleware::getCurrentUser();
    }

    public function index()
    {
        $data['judul'] = 'Halaman Dasboard';
        $this->view('layouts/header/header', $data);
        $this->view('layouts/sidebar/sidebar');
        $this->view('home/index', $data);
        $this->view('layouts/footer/footer');
    }
}
