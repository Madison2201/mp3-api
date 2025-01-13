<?php

namespace api\services;

use api\forms\PostForm;
use api\interface\repositories\PostRepositoryInterface;
use api\interface\services\PostServiceInterface;
use api\models\Post;
use api\repositories\PostRepository;
use yii\web\ForbiddenHttpException;

class PostService implements PostServiceInterface
{
    private PostRepository $posts;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->posts = $postRepository;
    }

    public function getPosts($params)
    {
        return $this->posts->search($params);
    }

    public function getPost(int $id)
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

    public function remove(int $id): void
    {
        $post = $this->posts->getById($id);
        if ($post->user_id !== \Yii::$app->user->id) {
            throw new ForbiddenHttpException('Вы не можете производить данное действие');
        }
        $this->posts->remove($post);
    }
}