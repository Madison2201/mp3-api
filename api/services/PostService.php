<?php

namespace api\services;

use api\forms\PostForm;
use api\interface\repositories\PostRepositoryInterface;
use api\interface\services\PostServiceInterface;
use api\models\Post;
use api\repositories\PostRepository;

class PostService implements PostServiceInterface
{
    private PostRepository $posts;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->posts = $postRepository;
    }

    public function getPosts()
    {
        return $this->posts->getAll();
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

    public function edit(int $id, PostForm $form): void
    {
        $post = $this->brands->get($id);
        $post->edit(
            $form->name,
            $form->slug,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );
        $this->brands->save($post);
    }

    public function remove(int $id): void
    {
        $post = $this->brands->get($id);
        if ($this->products->existsByBrand($post->id)) {
            throw new \DomainException('Brand can not be removed, it is already used by other product.');
        }
        $this->brands->remove($post);
    }
}