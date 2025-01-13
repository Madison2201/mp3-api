<?php

namespace api\interface\repositories;

use api\models\Post;
use yii\data\ActiveDataProvider;

interface PostRepositoryInterface
{
    public function getAll(): array;

    public function save(Post $post): void;

    public function remove(Post $post): void;

    public function search($params): ActiveDataProvider;

    public function getById(int $id): Post;


}