<?php

namespace api\controllers;

use api\components\JwtAuth;
use api\forms\PostForm;
use api\helpers\FileHelper;
use api\interface\services\PostServiceInterface;
use api\services\PostService;
use Yii;
use yii\filters\VerbFilter;
use yii\rest\Controller;
use yii\web\Response;
use yii\web\UploadedFile;

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

    public function actionIndex(): array
    {
        return $this->service->getPosts();
    }

    /**
     * @throws \Exception
     */
    public function actionCreate(): Response|array
    {
        $form = new PostForm();
        $form->setAttributes(Yii::$app->request->post());
        $form->file = FileHelper::upload(UploadedFile::getInstanceByName('file'));
        if ($form->validate()) {

            $post = $this->service->create($form);
            return [
                'success' => true,
                'id' => $post->id,
                'title' => $post->title,
            ];

        }
        return [
            'success' => false,
            'errors' => $form->getErrors(),
        ];

    }

    public function actionUpdate(int $id): Response|array
    {
        $post = $this->service->getPost($id);
        $form = new PostForm($post);
        $form->setAttributes(Yii::$app->request->post());
        $form->file = FileHelper::upload(UploadedFile::getInstanceByName('file'));
        if ($form->validate()) {

            $this->service->edit($post, $form);
            return [
                'success' => true,
                'id' => $post->id,
                'message' => 'Post updated successfully',
            ];

        }
        return [
            'success' => false,
            'errors' => $form->getErrors(),
        ];

    }

    public function actionDelete(int $id): Response|array
    {
        $this->service->remove($id);
        return [
            'success' => true,
            'message' => 'Post deleted successfully',
        ];
    }
}