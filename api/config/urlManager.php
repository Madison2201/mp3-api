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

        'GET /tag' => 'tag/index',
        'POST /tag' => 'tag/create',
        'POST /attach-tag/' => 'tag/attach-tag',
        'DELETE /attach-tag/' => 'tag/detach-tag',
        'PUT /tag/<id:\d+>' => 'tag/update',
        'DELETE /tag/<id:\d+>' => 'tag/delete',
    ],
];