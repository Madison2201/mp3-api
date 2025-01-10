<?php

namespace api\services;

use api\forms\TagAssignmentsForm;
use api\forms\TagForm;
use api\interface\repositories\TagRepositoryInterface;
use api\interface\services\TagServiceInterface;
use api\models\Tag;
use api\models\TagAssignments;
use api\repositories\TagRepository;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;

class TagService implements TagServiceInterface
{
    private TagRepository $tags;

    public function __construct(TagRepositoryInterface $tags)
    {
        $this->tags = $tags;
    }

    public function getAll(): array
    {
        return $this->tags->getAll();
    }

    public function create(TagForm $form): Tag
    {
        $tag = Tag::create($form->title);
        $this->tags->save($tag);
        return $tag;
    }

    /**
     * @throws NotFoundHttpException
     */
    public function getTag(int $id): Tag
    {
        return $this->tags->getById($id);
    }

    public function edit(Tag $tag, TagForm $form): void
    {
        $tag->edit(
            $form->title,
        );
        $this->tags->save($tag);
    }

    /**
     * @throws StaleObjectException
     * @throws \Throwable
     * @throws NotFoundHttpException
     */
    public function remove(int $id): void
    {
        $tag = $this->tags->getById($id);
        $this->tags->remove($tag);
    }


}