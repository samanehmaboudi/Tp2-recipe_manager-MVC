<?php 

namespace App\Controllers;
use App\Models\Recipe;
use App\Providers\View;
use App\Providers\Validator;
use App\Models\Category;

class RecipeController {

    public function index() {
        $recipe = new Recipe;
        $select = $recipe->select('title'); 
        return View::render('recipe/index', ['recipes' => $select]);
    }

    public function create() {
        $category = new Category;
        $categories = $category->all();
        return View::render('recipe/create', ['categories' => $categories]);
    
    }

    public function show($data = []) {
        if (!isset($data['id']) || empty($data['id'])) {
            return View::render('error', ['msg' => 'Recipe ID is missing']);
        }

        $recipe = new Recipe;
        if ($selectId = $recipe->selectId($data['id'])) {
            return View::render('recipe/show', ['recipe' => $selectId]);
        } else {
            return View::render('error', ['msg' => 'Recipe does not exist']);
        }
    }

    public function store($data = []) {
        $validator = new Validator;
        $validator->field('title', $data['title'] ?? '')->min(3)->max(100);
        $validator->field('ingredients', $data['ingredients'] ?? '')->required();
        $validator->field('instructions', $data['instructions'] ?? '')->required();

        if ($validator->isSuccess()) {
            $recipe = new Recipe;
            $insert = $recipe->insert($data);
            if ($insert) {
                return View::redirect('recipe/show?id=' . $insert);
            } else {
                return View::render('error', ['msg' => 'Error inserting the recipe']);
            }
        } else {
            $errors = $validator->getErrors();
            return View::render('recipe/create', ['errors' => $errors, 'recipe' => $data]);
        }
    }

    public function edit($data = []) {
        if (!isset($data['id']) || empty($data['id'])) {
            return View::render('error', ['msg' => 'Recipe ID is missing']);
        }

        $recipe = new Recipe;
        if ($selectId = $recipe->selectId($data['id'])) {
            return View::render('recipe/edit', ['recipe' => $selectId]);
        } else {
            return View::render('error', ['msg' => 'Recipe does not exist']);
        }
    }

    public function update($data = [], $get = []) {
        if (!isset($get['id']) || empty($get['id'])) {
            return View::render('error', ['msg' => 'Recipe ID is missing']);
        }

        $validator = new Validator;
        $validator->field('title', $data['title'] ?? '')->min(3)->max(100);
        $validator->field('ingredients', $data['ingredients'] ?? '')->required();
        $validator->field('instructions', $data['instructions'] ?? '')->required();

        if ($validator->isSuccess()) {
            $recipe = new Recipe;
            $update = $recipe->update($data, $get['id']);
            if ($update) {
                return View::redirect('recipe/show?id=' . $get['id']);
            } else {
                return View::render('error', ['msg' => 'Error updating the recipe']);
            }
        } else {
            $errors = $validator->getErrors();
            return View::render('recipe/edit', ['errors' => $errors, 'recipe' => $data]);
        }
    }

    public function delete($data = []) {
        if (!isset($data['id']) || empty($data['id'])) {
            return View::render('error', ['msg' => 'Recipe ID is missing']);
        }

        $recipe = new Recipe;
        $delete = $recipe->delete($data['id']);
        if ($delete) {
            return View::redirect('recipes');
        } else {
            return View::render('error', ['msg' => 'Error deleting the recipe']);
        }
    }
}
