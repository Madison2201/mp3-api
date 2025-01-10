<?php

return [
    'class' => 'yii\web\UrlManager',
    'hostInfo' => 'http://example.com',
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        'GET /post' => 'post/index',
        'POST /post' => 'post/create',
        'PUT /post/<id:\d+>' => 'post/update',
        'DELETE /post/<id:\d+>' => 'post/delete',
    ],
];