<?php

namespace api\controllers;

use api\components\JwtAuth;
use api\forms\PostForm;
use api\interface\services\PostServiceInterface;
use api\services\PostService;
use Yii;
use yii\filters\VerbFilter;
use yii\rest\Controller;

class PostController extends Controller
{

    private PostService $service;

    public function __construct($id, $module, PostServiceInterface $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'jwtAuth' => [
                    'class' => JwtAuth::class,
                ],
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'index' => ['get'],
                        'create' => ['post'],
                        'update' => ['put'],
                        'delete' => ['delete'],
                    ],
                ],
            ]
        );
    }

    public function actionIndex()
    {
        return $this->service->getPosts();
    }

    public function actionCreate()
    {
        $form = new PostForm();
        $form->setAttributes(Yii::$app->request->post());
        if ($form->validate()) {

            $post = $this->service->create($form);
            return $this->redirect(['view', 'id' => $post->id]);

        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'success' => false,
            'errors' => $form->getErrors(),
        ];

    }
}