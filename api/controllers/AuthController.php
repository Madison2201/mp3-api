<?php

namespace api\controllers;

use api\interface\services\AuthServiceInterface;
use api\services\AuthService;
use common\models\LoginForm;
use Yii;
use yii\filters\VerbFilter;
use yii\rest\Controller;
use yii\web\Response;

class AuthController extends Controller
{

    private AuthService $service;

    public function __construct($id, $module, AuthServiceInterface $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }
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
        if ($form->validate()) {
            return $this->service->auth($form);

        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'success' => false,
            'errors' => $form->getErrors(),
        ];
    }
}