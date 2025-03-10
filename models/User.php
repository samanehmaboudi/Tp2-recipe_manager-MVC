<?php    

namespace App\Models;
use App\Models\CRUD;

class User extends CRUD {
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'email', 'password'];

    // Insérer un utilisateur (déjà défini dans CRUD, donc pas besoin de le redéfinir ici)
    public function createUser(array $data) {
        if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
            throw new \Exception("All fields are required.");
        }

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        return $this->insert($data); // Utiliser `insert()` de CRUD.php
    }

    // Trouver un utilisateur par son ID
    public function findUser($id) {
        return $this->find($id); // Utiliser `find()` de CRUD.php
    }

    // Mettre à jour un utilisateur
    public function updateUser($id, array $data) {
        return $this->update($id, $data); // Utiliser `update()` de CRUD.php
    }

    // Supprimer un utilisateur
    public function deleteUser($id) {
        return $this->delete($id); // Utiliser `delete()` de CRUD.php
    }
}
