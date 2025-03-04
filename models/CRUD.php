<?php  

namespace App\Models;
use PDO;
use PDOException;

abstract class CRUD {
    protected $pdo;
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];

    public function __construct() {
        $host = 'localhost';
        $dbname = 'recipe_manager'; 
        $username = 'root';
        $password = 'root';

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    /**
     * Récupérer toutes les entrées de la table
     */
    public function select($field = null, $order = 'ASC') {
        $field = $field ?? $this->primaryKey;
        $sql = "SELECT * FROM {$this->table} ORDER BY $field $order";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Récupérer une entrée par son ID
     */
    public function selectId($id) {
        return $this->find($id);
    }

    /**
     * Récupérer toutes les entrées
     */
    public function all() {
        $sql = "SELECT * FROM {$this->table}";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Rechercher une entrée par ID
     */
    public function find($id) {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        return $stmt->fetch() ?: false;
    }

    /**
     * Insérer une nouvelle entrée
     */
    public function insert($data) {
        $data = array_intersect_key($data, array_flip($this->fillable));
        $fields = implode(', ', array_keys($data));
        $placeholders = ":" . implode(', :', array_keys($data));

        $sql = "INSERT INTO {$this->table} ($fields) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute() ? $this->pdo->lastInsertId() : false;
    }

    /**
     * Mettre à jour une entrée existante
     */
    public function update($data, $id) {
        if (!$this->find($id)) {
            return false;
        }

        $data = array_intersect_key($data, array_flip($this->fillable));
        $fieldString = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($data)));
        $sql = "UPDATE {$this->table} SET $fieldString WHERE {$this->primaryKey} = :id";

        $stmt = $this->pdo->prepare($sql);
        $data['id'] = $id;

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }

    /**
     * Supprimer une entrée par son ID
     */
    public function delete($id) {
        if (!$this->find($id)) {
            return false;
        }

        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":id", $id);
        return $stmt->execute();
    }
}
