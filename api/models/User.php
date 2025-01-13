<?php

namespace api\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use sizeg\jwt\Jwt;
use yii\base\InvalidArgumentException;

/**
 * User model
 *
 * @property int $id
 * @property string $email
 * @property string $password_hash
 * @property int $created_at
 * @property int $updated_at
 */
class User extends ActiveRecord implements IdentityInterface
{
    const SCENARIO_REGISTER = 'register';

    public static function tableName()
    {
        return '{{%user}}';
    }

    public function rules()
    {
        return [
            [['email', 'password_hash'], 'required'],
            ['email', 'email'],
            ['email', 'unique'],
            ['password_hash', 'string', 'min' => 6],
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {

        $jwt = Yii::$app->jwt;
        try {
            $data = $jwt->decode($token);
            return static::findOne($data['user_id']);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return '';
    }

    public function validateAuthKey($authKey)
    {
        return false;
    }


    public static function createUser($email, $password)
    {
        $user = new self();
        $user->email = $email;
        $user->password_hash = Yii::$app->getSecurity()->generatePasswordHash($password);
        $user->created_at = time();
        $user->updated_at = time();
        return $user;
    }


    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password_hash);
    }

}
