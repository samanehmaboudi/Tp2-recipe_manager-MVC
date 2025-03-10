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
        $port = '3307';

        try {
            $this->pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    /**
     * Récupérer toutes les entrées de la table, avec tri sécurisé
     */
    public function select($field = null, $order = 'ASC') {
        $field = in_array($field, $this->fillable) ? $field : $this->primaryKey;
        $order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';

        $sql = "SELECT * FROM {$this->table} ORDER BY $field $order";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
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
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch() ?: false;
    }

    /**
     * Insérer une nouvelle entrée en validant les champs
     */
    public function insert($data) {
        $data = array_intersect_key($data, array_flip($this->fillable));

        if (count($data) !== count($this->fillable)) {
            return false; // S'assurer que tous les champs requis sont présents
        }

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
     * Mettre à jour une entrée existante avec validation
     */
    public function update($id, $data) {
        if (!$this->find($id)) {
            return false;
        }

        $data = array_intersect_key($data, array_flip($this->fillable));

        if (empty($data)) {
            return false;
        }

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
     * Supprimer une entrée par son ID en vérifiant son existence
     */
    public function delete($id) {
        if (!$this->find($id)) {
            return false;
        }

        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
