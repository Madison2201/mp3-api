<?php

namespace api\models;

use api\enums\PostStatus;
use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
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

    public function fields()
    {
        return [
            'id',
            'title',
            'description',
            'status',
            'created_at' => function () {
                return date('Y-m-d H:i:s', $this->created_at);
            },
        ];
    }

    /**
     * @throws InvalidConfigException
     */
    public function getTags(): ActiveQuery
    {
        return $this->hasMany(Tag::class, ['id' => 'id_tag'])
            ->viaTable('tag_assignments', ['id_post' => 'id']);
    }

    public function extraFields()
    {
        return [
            'tags',
            'file' => function () {
                $audioData = stream_get_contents($this->file);
                return base64_encode($audioData);
            } ,
        ];
    }

    public function search(array $params): ActiveDataProvider
    {
        $query = self::find()->alias('p');

        $query->joinWith('tags t');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $params['pageSize'] ?? 10,
            ],
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
                'attributes' => [
                    'id',
                    'title',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);

        $this->setAttributes($params, false);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['like', 'title', $this->title]);
        $query->andFilterWhere(['status' => $this->status]);
        $query->andFilterWhere(['user_id' => $this->user_id]);
        if (!empty($this->created_at)) {
            $timestamp = strtotime($this->created_at);
            $query->andFilterWhere(['=', 'created_at', $timestamp]);
        }
        if (!empty($params['id_tag'])) {
            $query->andWhere(['t.id' => $params['id_tag']]);
        }

        return $dataProvider;
    }

}