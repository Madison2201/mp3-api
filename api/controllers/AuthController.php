<?php

namespace api\controllers;

use api\interface\services\AuthServiceInterface;
use api\services\AuthService;
use common\models\LoginForm;
use Yii;
use yii\filters\VerbFilter;
use yii\rest\Controller;
use OpenApi\Attributes as OA;

#[OA\Info(version: "0.1", title: "Mp3 api")]
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

    #[OA\Post(
        path: '/auth/login',
        description: 'Метод выполняет авторизацию пользователя с использованием предоставленных учетных данных.',
        summary: 'Авторизация пользователя',
        tags: ['Auth']
    )]
    #[OA\RequestBody(
        description: 'Данные для входа',
        required: true,
        content: new OA\JsonContent(
            required: ['email', 'password'],
            properties: [
                new OA\Property(property: 'email', type: 'string', example: 'user@example.com'),
                new OA\Property(property: 'password', type: 'string', example: 'password123'),
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Успешная авторизация',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'token', type: 'string', example: 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...'),
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Ошибка валидации',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: false),
                new OA\Property(property: 'errors', type: 'object', example: ['username' => 'Не может быть пустым'],)
            ],
            type: 'object'
        )
    )]
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