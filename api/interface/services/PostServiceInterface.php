<?php

namespace api\interface\services;

use api\forms\PostForm;
use api\models\Post;

interface PostServiceInterface
{
    public function getPosts(array $params);


    public function getPost(int $id);


    public function create(PostForm $form): Post;


    public function edit(Post $post, PostForm $form): void;


    public function remove(int $id): void;

}