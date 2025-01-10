<?php

namespace api\repositories;

use api\interface\repositories\PostRepositoryInterface;
use api\models\Post;
use yii\db\StaleObjectException;

class PostRepository implements PostRepositoryInterface
{
    public function getAll(): array
    {
        return Post::find()->all();
    }

    public function getById(int $id): Post
    {
        if (!$post = Post::findOne($id)) {
            throw new \RuntimeException('Post is not found.');
        }
        return $post;
    }

    public function save(Post $post): void
    {
        try {
            $post->save();
        } catch (\Exception $e) {
            throw new \RuntimeException('Saving error.');
        }
    }

    /**
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function remove(Post $post): void
    {
        if (!$post->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }
}