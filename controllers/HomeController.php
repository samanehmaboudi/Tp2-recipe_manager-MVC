<?php 

namespace App\Controllers;

use App\Providers\View;

class HomeController {

    public function index(): void {
        View::render('home');
    }

}
