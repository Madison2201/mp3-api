<?php

namespace api\controllers;

use yii\filters\VerbFilter;
use yii\rest\Controller;

class HelloController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
    public function actionIndex(): string
    {
        return "Hello World";
    }
}