<?php

namespace App\Controllers;
use App\Providers\View;
use App\Models\Category;

class CategoryController {
    
    public function index() {
        $category = new Category;
        $categories = $category->select();
        return View::render('category/index', ['categories' => $categories]);
    }

    public function store($data = []) {
        $category = new Category;
        $category->insert($data);
        return View::redirect('/categories');
    }

    public function update($data = [], $get = []) {
        $category = new Category;
        $category->update($data, $get['id']);
        return View::redirect('/categories');
    }

    public function delete($data = []) {
        $category = new Category;
        $category->delete($data['id']);
        return View::redirect('/categories');
    }
}
