<?php

namespace api\interface\services;

use api\forms\TagForm;
use api\models\Tag;
use yii\data\ActiveDataProvider;

interface TagServiceInterface
{
    public function getAll($params): ActiveDataProvider;


    public function create(TagForm $form): Tag;


    public function getTag(int $id): Tag;


    public function edit(Tag $tag, TagForm $form): void;


    public function remove(int $id): void;


}