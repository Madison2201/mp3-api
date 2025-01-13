<?php

namespace api\services;

use api\forms\PostForm;
use api\interface\repositories\PostRepositoryInterface;
use api\interface\services\PostServiceInterface;
use api\models\Post;
use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class PostService implements PostServiceInterface
{
    private PostRepositoryInterface $posts;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->posts = $postRepository;
    }

    public function getPosts(array $params): ActiveDataProvider
    {
        return $this->posts->search($params);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function getPost(int $id): Post
    {
        return $this->posts->getById($id);
    }

    public function create(PostForm $form): Post
    {
        $post = Post::create(
            $form->title,
            $form->description,
            $form->file
        );
        $this->posts->save($post);
        return $post;
    }

    public function edit(Post $post, PostForm $form): void
    {
        $post->edit(
            $form->title,
            $form->description,
            $form->file,
            $form->status
        );
        $this->posts->save($post);
    }

    /**
     * @throws Throwable
     * @throws ForbiddenHttpException
     */
    public function remove(int $id): void
    {
        $post = $this->posts->getById($id);
        if ($post->user_id !== \Yii::$app->user->id) {
            throw new ForbiddenHttpException(Yii::t('app', 'forbidden_error'));
        }
        $this->posts->remove($post);
    }
}