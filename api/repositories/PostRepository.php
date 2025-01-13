<?php

namespace api\repositories;

use api\interface\repositories\PostRepositoryInterface;
use api\models\Post;
use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;

class PostRepository implements PostRepositoryInterface
{
    public function getAll(): array
    {
        return Post::find()->all();
    }

    public function search($params): ActiveDataProvider
    {
        $model = new Post();
        return $model->search($params);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function getById(int $id): Post
    {
        if (!$post = Post::findOne($id)) {
            throw new NotFoundHttpException(Yii::t('app', 'post_not_found'));
        }
        return $post;
    }

    public function save(Post $post): void
    {
        try {
            $post->save();
        } catch (\Exception $e) {
            throw new \RuntimeException(Yii::t('app', 'save_error'));
        }
    }

    /**
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function remove(Post $post): void
    {
        if (!$post->delete()) {
            throw new \RuntimeException(Yii::t('app', 'remove_error'));
        }
    }
}