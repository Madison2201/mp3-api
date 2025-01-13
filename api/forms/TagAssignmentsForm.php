<?php

namespace api\forms;

use api\models\Post;
use api\models\Tag;
use api\models\TagAssignments;
use yii\base\Model;

class TagAssignmentsForm extends Model
{
    public int $id_tag;
    public int $id_post;
    const SCENARIO_DELETE = 'delete';
    public function rules(): array
    {
        return [
            [['id_tag', 'id_post'], 'required'],
            [['id_tag', 'id_post'], 'integer', 'min' => 1, 'max' => PHP_INT_MAX],
            ['id_tag', 'exist', 'targetClass' => Tag::class, 'targetAttribute' => 'id', 'message' => 'Tag ID не найден в таблице tags.'],
            ['id_post', 'exist', 'targetClass' => Post::class, 'targetAttribute' => 'id', 'message' => 'Post ID не найден в таблице posts.'],
            ['id_post', 'validateUniqueAssignment', 'on' => self::SCENARIO_DEFAULT],
            ['id_post', 'existAssignment', 'on' => self::SCENARIO_DELETE],
        ];
    }

    public function validateUniqueAssignment($attribute, $params): void
    {
        $exists = TagAssignments::find()
            ->where(['id_post' => $this->id_post, 'id_tag' => $this->id_tag])
            ->exists();
        if ($exists) {
            $this->addError($attribute, 'Такая связь уже существует.');
        }
    }

    public function existAssignment($attribute, $params): void
    {
        $exists = TagAssignments::find()
            ->where(['id_post' => $this->id_post, 'id_tag' => $this->id_tag])
            ->exists();
        if (!$exists) {
            $this->addError($attribute, 'Такой связи не существует.');
        }
    }
}