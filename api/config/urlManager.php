<?php

return [
    'class' => 'yii\web\UrlManager',
    'hostInfo' => 'http://example.com',
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        '<_a:[\w\-]+>' => 'site/<_a>',
        '' => 'site/index',
        '<_a:login|logout>' => 'site/<_a>',
        '<_c:[\w\-]+>' => '<_c>/index',
        '<_c:[\w\-]+>/<id:\d+>' => '<_c>/view',
        '<_c:[\w\-]+>/<_a:[\w-]+>' => '<_c>/<_a>',
        '<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => '<_c>/<_a>',
    ],
];