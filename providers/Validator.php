<?php 

namespace App\Providers;

class Validator {

    private $errors = [];
    private $key;
    private $value;
    private $name;

    public function field($key, $value, $name = null) {
        $this->key = $key;
        $this->value = trim($value); // Nettoyage des espaces
        $this->name = $name ? ucfirst($name) : ucfirst($key);
        return $this;
    }

    public function required() {
        if (empty($this->value)) {
            $this->errors[$this->key] = "$this->name is required!";
        }
        return $this;
    }

    public function min($length) {
        if (strlen($this->value) < $length) {
            $this->errors[$this->key] = "$this->name must be at least $length characters!";
        }
        return $this;
    }

    public function max($length) {
        if (strlen($this->value) > $length) {
            $this->errors[$this->key] = "$this->name must be less than $length characters!";
        }
        return $this;
    }

    public function minMax($min, $max) {
        return $this->min($min)->max($max);
    }

    public function number() {
        if (!empty($this->value) && !is_numeric($this->value)) {
            $this->errors[$this->key] = "$this->name must be a number!";
        }
        return $this;	    
    }

    public function alpha() {
        if (!empty($this->value) && !ctype_alpha(str_replace(' ', '', $this->value))) {
            $this->errors[$this->key] = "$this->name must contain only letters!";
        }
        return $this;
    }

    public function validate() {
        return empty($this->errors);
    }

    public function isSuccess() {  // ✅ Ajout de cette méthode
        return empty($this->errors);
    }

    public function getErrors() {
        return $this->errors;
    }
}
