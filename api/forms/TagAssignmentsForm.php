<?php

namespace api\forms;

use api\models\Post;
use api\models\Tag;
use api\models\TagAssignments;
use Yii;
use yii\base\Model;

class TagAssignmentsForm extends Model
{
    public  $id_tag;
    public  $id_post;
    const SCENARIO_DELETE = 'delete';
    public function rules(): array
    {
        return [
            [['id_tag', 'id_post'], 'required'],
            [['id_tag', 'id_post'], 'integer', 'min' => 1, 'max' => PHP_INT_MAX],
            ['id_tag', 'exist', 'targetClass' => Tag::class, 'targetAttribute' => 'id', 'message' => Yii::t('app', 'id_tag_not_found')],
            ['id_post', 'exist', 'targetClass' => Post::class, 'targetAttribute' => 'id', 'message' => Yii::t('app', 'id_post_not_found')],
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
            $this->addError($attribute, Yii::t('app', 'record_already_exists'));
        }
    }

    public function existAssignment($attribute, $params): void
    {
        $exists = TagAssignments::find()
            ->where(['id_post' => $this->id_post, 'id_tag' => $this->id_tag])
            ->exists();
        if (!$exists) {
            $this->addError($attribute, Yii::t('app', 'record_does_not_exists'));
        }
    }
}