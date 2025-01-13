<?php

namespace api\models;

use yii\data\ActiveDataProvider;
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

    public function search(array $params): ActiveDataProvider
    {
        $query = self::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $params['pageSize'] ?? 10,
            ],
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
                'attributes' => ['id', 'title',],
            ],
        ]);

        $this->setAttributes($params, false);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}