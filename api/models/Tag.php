<?php

namespace api\models;

use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property string $title
 */
class Tag extends ActiveRecord
{
    public static function tableName(): string
    {
        return "{{%tags}}";
    }

    public static function create(string $name): Tag
    {
        $tag = new static();
        $tag->title = $name;
        return $tag;
    }

    public function edit(string $name): void
    {
        $this->title = $name;
    }
}