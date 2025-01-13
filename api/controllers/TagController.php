<?php

namespace api\controllers;

use api\components\JwtAuth;
use api\forms\TagAssignmentsForm;
use api\forms\TagForm;
use api\interface\services\TagAssignmentsServiceInterface;
use api\interface\services\TagServiceInterface;
use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\filters\VerbFilter;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;

class TagController extends Controller
{
    private TagServiceInterface $service;
    private TagAssignmentsServiceInterface $tagAssignmentsService;

    public function __construct($id, $module, TagServiceInterface $service, TagAssignmentsServiceInterface $tagAssignmentsService, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->tagAssignmentsService = $tagAssignmentsService;
    }

    public function behaviors(): array
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
                        'attach-tag' => ['post'],
                        'index' => ['get'],
                        'create' => ['post'],
                        'update' => ['put'],
                        'delete' => ['delete'],
                        'detach-tag' => ['delete'],
                    ],
                ],
            ]
        );
    }

    public function actionAttachTag(): array
    {
        $form = new TagAssignmentsForm();
        $form->setAttributes(Yii::$app->request->post());
        if ($form->validate()) {
            $this->tagAssignmentsService->attachTag($form);
            return [
                'success' => true,
            ];
        }
        return [
            'success' => false,
            'errors' => $form->getErrors(),
        ];

    }

    public function actionDetachTag(): array
    {
        $form = new TagAssignmentsForm();
        $form->setScenario(TagAssignmentsForm::SCENARIO_DELETE);
        $form->setAttributes(Yii::$app->request->post());
        if ($form->validate()) {
            $this->tagAssignmentsService->detachTag($form);
            return [
                'success' => true,
            ];
        }
        return [
            'success' => false,
            'errors' => $form->getErrors(),
        ];

    }

    public function actionIndex(): ActiveDataProvider
    {
        $params = Yii::$app->request->queryParams;
        return $this->service->getAll($params);
    }

    public function actionCreate(): array
    {
        $form = new TagForm();
        $form->setAttributes(Yii::$app->request->post());
        if ($form->validate()) {

            $tag = $this->service->create($form);
            return [
                'success' => true,
                'id' => $tag->id,
                'title' => $tag->title,
            ];

        }
        return [
            'success' => false,
            'errors' => $form->getErrors(),
        ];
    }

    public function actionUpdate($id): array
    {
        $tag = $this->service->getTag($id);
        $form = new TagForm($tag);
        $form->setAttributes(Yii::$app->request->post());
        if ($form->validate()) {

            $this->service->edit($tag, $form);
            return [
                'success' => true,
                'id' => $tag->id,
                'message' => Yii::t('app', 'tag_deleted_successfully'),
            ];

        }
        return [
            'success' => false,
            'errors' => $form->getErrors(),
        ];
    }

    /**
     * @throws Throwable
     */
    public function actionDelete($id): array
    {
        $this->service->remove($id);
        return [
            'success' => true,
            'message' => Yii::t('app', 'tag_deleted_successfully'),
        ];
    }
}