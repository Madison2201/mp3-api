<?php

namespace api\interface\repositories;

use api\models\Post;

interface PostRepositoryInterface
{
    public function getAll(): array;

    public function save(Post $post): void;

    public function remove(Post $post): void;

}