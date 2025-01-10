<?php

use api\interface\repositories\UserRepositoryInterface;
use api\interface\repositories\PostRepositoryInterface;
use api\interface\services\AuthServiceInterface;
use api\interface\services\PostServiceInterface;
use api\repositories\PostRepository;
use api\repositories\UserRepository;
use api\services\AuthService;
use api\services\PostService;

$params = array_merge(
    require __DIR__ . '/../../api/config/params.php',
    require __DIR__ . '/../../api/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'jwt' => [
            'class' => api\components\JwtService::class,
        ],
        'request' => [
            'class' => '\yii\web\Request',
            'enableCookieValidation' => false,
            'enableCsrfValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
                'multipart/form-data' => 'yii\web\MultipartFormDataParser',
            ]
        ],
        'response' => [
            'format' =>'json',
            'charset' => 'UTF-8',
            'formatters' => [
                'json' => [
                    'class' => 'yii\web\JsonResponseFormatter',
                    'prettyPrint' => YII_DEBUG,
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                ],
                'xml' => 'yii\web\XmlResponseFormatter',
            ],
        ],
        'user' => [
            'identityClass' => 'api\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-api', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-api',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => require __DIR__ . '/../../api/config/urlManager.php',
    ],
    'container' => [
        'definitions' => [
            PostServiceInterface::class => PostService::class,
            PostRepositoryInterface::class => PostRepository::class,
            AuthServiceInterface::class => AuthService::class,
            UserRepositoryInterface::class => UserRepository::class,
        ],
    ],
    'params' => $params,
];
