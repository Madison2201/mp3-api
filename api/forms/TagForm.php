<?php

namespace api\forms;

use yii\base\Model;

class TagForm extends Model
{
    public $title;
    public $id;

    public function rules(): array
    {
        return [
            ['title', 'required'],
            ['title', 'string', 'max' => 255],
        ];
    }
}