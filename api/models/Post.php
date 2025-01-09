<?php

namespace api\models;

use api\enums\PostStatus;
use Yii;
use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property file $file
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 * @property integer $user_id
 */
class Post extends ActiveRecord
{
    public static function tableName(): string
    {
        return "{{%post}}";
    }

    public static function create(string $title, string $description, $file): self
    {
        $post = new static();
        $post->title = $title;
        $post->description = $description;
        $post->file = $file;
        $post->created_at = time();
        $post->status = PostStatus::ACTIVE->value;
        $post->user_id = Yii::$app->user->id;
        return $post;
    }

    public function edit(string $title, string $description, $file, int $status): void
    {
        $this->title = $title;
        $this->description = $description;
        $this->file = $file;
        $this->status = $status;
        $this->updated_at = time();
    }
}