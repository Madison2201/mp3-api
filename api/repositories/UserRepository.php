<?php

namespace api\repositories;

use api\enums\UserStatus;
use api\interface\repositories\UserRepositoryInterface;
use api\models\User;
use DomainException;
use Yii;
use yii\db\ActiveRecord;

class UserRepository implements UserRepositoryInterface
{
    public function getByEmail(string $email): array|ActiveRecord
    {
        return $this->getBy([
            'status' => UserStatus::ACTIVE->value,
            'email' => $email,
        ]);
    }

    private function getBy(array $condition): array|ActiveRecord
    {
        if (!$user = User::find()->andWhere($condition)->limit(1)->one()) {
            throw new  DomainException(Yii::t('app', 'user_not_found'));
        }
        return $user;
    }
}