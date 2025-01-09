<?php

namespace api\repositories;

use api\interface\repositories\PostRepositoryInterface;
use api\models\Post;

class PostRepository implements PostRepositoryInterface
{
    public function getAll(): array
    {
        return Post::find()->all();
    }

    public function save(Post $post): void
    {
        try {
            $post->save();
        } catch (\Exception $e) {
            throw new \RuntimeException('Saving error.');
        }
    }

    public function remove(Post $post): void
    {
        if (!$post->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }
}