<?php  

namespace App\Models;
use App\Models\CRUD;

class User extends CRUD {
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = ['username', 'password', 'email'];
}
