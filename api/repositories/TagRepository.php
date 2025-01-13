<?php

namespace api\repositories;

use api\interface\repositories\TagRepositoryInterface;
use api\models\Tag;
use Throwable;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;

class TagRepository implements TagRepositoryInterface
{
    public function getAll(): array
    {
        return Tag::find()->all();
    }

    public function save(Tag $tag): void
    {
        try {
            $tag->save();
        } catch (\Exception $e) {
            throw new \RuntimeException('Saving error.');
        }
    }

    /**
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function remove(Tag $tag): void
    {
        if (!$tag->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }

    /**
     * @throws NotFoundHttpException
     */
    public function getById(int $id): Tag
    {
        if (!$tag = Tag::findOne($id)) {
            throw new NotFoundHttpException('Tag is not found.');
        }
        return $tag;
    }

    public function search($params): ActiveDataProvider
    {
        $tags = new Tag();
        return $tags->search($params);
    }
}