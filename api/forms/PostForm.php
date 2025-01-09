<?php

namespace api\forms;

use yii\base\Model;

class PostForm extends Model
{
    public $title;
    public $description;
    public $file;
    public $created_at;
    public $updated_at;
    public $status;

    public function rules()
    {
        return [
//            [['title', 'description', 'file'], 'required'],
            [['title', 'description'], 'string', 'max' => 255],
            [['file'], 'file'],
            [['file'], 'file', 'maxSize' => 20971520, 'tooBig' => 'Размер файла не должен превышать 20 MB.'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }
}