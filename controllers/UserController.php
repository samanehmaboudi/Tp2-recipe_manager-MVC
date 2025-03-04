<?php

namespace App\Controllers;
use App\Providers\View;
use App\Models\User;

class UserController {

    public function register() {

        return View::render('user/register');
    }

    public function store($data = []) {
        $user = new User;
        $user->insert($data);
        return View::redirect('/login');
    }


    public function authenticate($data = []) {
        // Logique d'authentification
        return View::redirect('/dashboard');
    }


}
