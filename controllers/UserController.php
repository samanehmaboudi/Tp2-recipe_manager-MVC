<?php  

namespace App\Controllers;
use App\Providers\View;
use App\Models\User;

class UserController {

    // Afficher la liste des utilisateurs
    public function index(): void {
        $user = new User();
        $users = $user->all(); // Récupère tous les utilisateurs
        View::render('user/index', ['users' => $users]);
    }

    // Afficher le formulaire d'inscription
    public function register(): void {
        $error = $_GET['error'] ?? null; // Récupérer une erreur si elle existe
        View::render('user/register', ['error' => $error]);
    }

    // Stocker un nouvel utilisateur
    public function store(array $data): void {
        if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
            View::redirect('/register?error=All fields are required');
            return;
        }

        $user = new User();
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $user->insert([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $hashedPassword
        ]);

        View::redirect('/users'); // Redirection vers la liste des utilisateurs
    }

    // Afficher un utilisateur spécifique
    public function show(int $id): void {
        $user = new User();
        $userData = $user->findUser($id);
        
        if (!$userData) {
            View::redirect('/users?error=User not found');
            return;
        }

        View::render('user/show', ['user' => $userData]);
    }

    // Afficher le formulaire d'édition d'un utilisateur
    public function edit(int $id): void {
        $user = new User();
        $userData = $user->findUser($id);

        if (!$userData) {
            View::redirect('/users?error=User not found');
            return;
        }

        View::render('user/edit', ['user' => $userData]);
    }

    // Mettre à jour un utilisateur
    public function update(int $id, array $data): void {
        $user = new User();
        $userData = $user->findUser($id);

        if (!$userData) {
            View::redirect('/users?error=User not found');
            return;
        }

        $user->updateUser($id, [
            'name' => $data['name'] ?? $userData['name'],
            'email' => $data['email'] ?? $userData['email']
        ]);

        View::redirect("/user/$id");
    }

    // Supprimer un utilisateur
    public function destroy(int $id): void {
        $user = new User();
        $userData = $user->findUser($id);

        if (!$userData) {
            View::redirect('/users?error=User not found');
            return;
        }

        $user->deleteUser($id);
        View::redirect('/users');
    }
}
