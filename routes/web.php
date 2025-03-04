<?php

use App\Controllers\HomeController;
use App\Controllers\RecipeController;
use App\Controllers\CategoryController;
use App\Controllers\UserController;
use App\Controllers\CommentController;
use App\Routes\Route;

// Page d'accueil affichant la liste des recettes
Route::get('/', 'HomeController@index');

// Routes pour les recettes (RecipeController)
Route::get('/recipes', 'RecipeController@index');    
Route::get('/recipe/create', 'RecipeController@create');  // Formulaire d'ajout de recette     // Liste des recettes
Route::get('/recipe/{id}', 'RecipeController@show');      // Détail d'une recette (avec ID dynamique)
Route::post('/recipe', 'RecipeController@store');         // Ajout d'une recette
Route::get('/recipe/{id}/edit', 'RecipeController@edit'); // Formulaire d'édition
Route::post('/recipe/{id}/edit', 'RecipeController@update'); // Modification d'une recette
Route::post('/recipe/{id}/delete', 'RecipeController@delete'); // Suppression d'une recette

// Routes pour les catégories (CategoryController)
Route::get('/categories', 'CategoryController@index');       // Liste des catégories
Route::post('/category', 'CategoryController@store');        // Ajouter une catégorie
Route::post('/category/{id}/edit', 'CategoryController@update'); // Modifier une catégorie
Route::post('/category/{id}/delete', 'CategoryController@delete'); // Supprimer une catégorie

// Routes pour les utilisateurs (UserController)
Route::get('/register', 'UserController@register');         // Formulaire d'inscription
Route::post('/register', 'UserController@store');           // Traitement de l'inscriptionnexion

// Routes pour les commentaires (CommentController)
Route::post('/recipe/{id}/comment', 'CommentController@store'); // Ajouter un commentaire à une recette
Route::post('/comment/{id}/delete', 'CommentController@delete'); // Supprimer un commentaire

// Démarrer le dispatching des routes
Route::dispatch();

