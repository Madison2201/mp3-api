<?php

namespace api\models;

use yii\db\ActiveRecord;

/**
 * @property int $id_post
 * @property int $id_tag
 */
class TagAssignments extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%tag_assignments}}';
    }
    public static function create(int $id_tag, int $id_post): self
    {
        $assignment = new self();
        $assignment->id_tag = $id_tag;
        $assignment->id_post = $id_post;
        return $assignment;
    }
}