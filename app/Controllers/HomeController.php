<?php
namespace App\Controllers;

class HomeController extends Controller {
    public function index() {
        $title = 'Bienvenido a Iglesia En Casa';
        $this->renderWithLayout('home/index', 'default', [
            'title' => $title,
            'user' => $this->getCurrentUser()
        ]);
    }
}