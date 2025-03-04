<?php

namespace App\Controllers;
use App\Providers\View;
use App\Models\Comment;

class CommentController {
    
    public function store($data = []) {
        $comment = new Comment;
        $comment->insert($data);
        return View::redirect('/recipe/' . $data['id']);
    }

    public function delete($data = []) {
        $comment = new Comment;
        $comment->delete($data['id']);
        return View::redirect('/recipes');
    }
}
