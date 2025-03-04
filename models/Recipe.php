<?php 

namespace App\Models;
use App\Models\CRUD;

class Recipe extends CRUD {
    protected $table = 'recipes';
    protected $primaryKey = 'id';
    protected $fillable = ['title', 'ingredients', 'instructions', 'category_id', 'active'];

    /**
     * Récupérer toutes les recettes
     */
    public function getAllRecipes() {
        return $this->all();
    }

    /**
     * Récupérer une recette par son ID
     */
    public function getRecipeById($id) {
        return $this->find($id);
    }
}
