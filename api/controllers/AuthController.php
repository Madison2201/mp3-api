<?php

namespace api\controllers;

use api\components\JwtService;
use api\models\User;
use common\models\LoginForm;
use Yii;
use yii\filters\VerbFilter;
use yii\rest\Controller;
use yii\web\UnauthorizedHttpException;

class AuthController extends Controller
{
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'login' => ['post'],
                    ],
                ],
            ]
        );
    }

    /**
     * Logs in a user.
     */
    public function actionLogin()
    {
        $form = new LoginForm();
        $form->setAttributes(Yii::$app->request->post());
        $body = Yii::$app->request->post();

        $user = User::findOne(['email' => $body['email']]);
        if (!$user || !$user->validatePassword($body['password'])) {
            return ['error' => 'Invalid email or password'];
        }

        $token = Yii::$app->jwt->createToken(['user_id' => $user->id]);

        return ['token' => $token];
    }
}