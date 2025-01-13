<?php

namespace api\services;

use api\interface\repositories\UserRepositoryInterface;
use api\interface\services\AuthServiceInterface;
use api\repositories\UserRepository;
use common\models\LoginForm;
use Yii;

class AuthService implements AuthServiceInterface
{
    private UserRepository $users;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->users = $userRepository;
    }

    public function auth(LoginForm $form): array
    {

        $token = Yii::$app->jwt->createToken(['user_id' => $form->getUserId()]);

        return [
            'name' => $form->getUsername(),
            'token' => 'jwt ' . $token
        ];
    }
}