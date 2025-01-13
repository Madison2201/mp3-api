<?php

namespace api\controllers;

use api\interface\services\AuthServiceInterface;
use api\services\AuthService;
use common\models\LoginForm;
use Yii;
use yii\filters\VerbFilter;
use yii\rest\Controller;

class AuthController extends Controller
{

    private AuthServiceInterface $service;

    public function __construct($id, $module, AuthServiceInterface $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    public function behaviors(): array
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
    public function actionLogin(): array
    {
        $form = new LoginForm();
        $form->setAttributes(Yii::$app->request->post());
        if ($form->validate()) {
            return $this->service->auth($form);

        }

        return [
            'success' => false,
            'errors' => $form->getErrors(),
        ];
    }
}