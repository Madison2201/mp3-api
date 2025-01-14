<?php

namespace api\controllers;

use api\components\JwtAuth;
use api\forms\TagAssignmentsForm;
use api\forms\TagForm;
use api\interface\services\TagAssignmentsServiceInterface;
use api\interface\services\TagServiceInterface;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\rest\Controller;
use OpenApi\Attributes as OA;

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

    #[OA\Post(
        path: '/tag/attach-tag',
        description: 'Прикрепление тэга к посту',
        summary: 'Прикрепление тэга к посту',
        tags: ['Tag'],

    )]
    #[OA\RequestBody(
        description: 'Данные для прикрепление',
        required: true,
        content: new OA\JsonContent(
            required: ['id_post', 'id_tag'],
            properties: [
                new OA\Property(property: 'id_post', type: 'integer', example: 1),
                new OA\Property(property: 'id_tag', type: 'integer', example: 2),
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Успешный ответ',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
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
                new OA\Property(property: 'errors', type: 'object', example: ['tag_id' => 'Tag ID не найден'],)
            ],
            type: 'object'
        )
    )]
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

    #[OA\Delete(
        path: '/tag/detach-tag',
        description: 'Открепление тэга от поста',
        summary: 'Открепление тэга от поста',
        tags: ['Tag'],

    )]
    #[OA\RequestBody(
        description: 'Данные для открепления',
        required: true,
        content: new OA\JsonContent(
            required: ['id_post', 'id_tag'],
            properties: [
                new OA\Property(property: 'id_post', type: 'integer', example: 1),
                new OA\Property(property: 'id_tag', type: 'integer', example: 2),
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Успешный ответ',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
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
                new OA\Property(property: 'errors', type: 'object', example: ['tag_id' => 'Tag ID не найден'],)
            ],
            type: 'object'
        )
    )]
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

    #[OA\Get(
        path: '/tag',
        description: 'Возвращает список всех тэгов с возможностью фильтрации',
        summary: 'Получить список всех тэгов',
        tags: ['Tag'],
        parameters: [
            new OA\Parameter(
                name: 'page',
                description: 'Номер страницы',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
            new OA\Parameter(
                name: 'pageSize',
                description: 'Количество элементов на страницы',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', example: 5)
            ),
            new OA\Parameter(
                name: 'sort',
                description: 'Сортировка',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', example: 'id')
            ),
        ]
    )]
    #[OA\Response(
        response: 200,
        description: 'Успешный ответ',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'id', type: 'integer', example: 1),
                new OA\Property(property: 'title', type: 'string', example: 'testr'),
            ],
            type: 'object'
        )
    )]
    public function actionIndex(): ActiveDataProvider
    {
        $params = Yii::$app->request->queryParams;
        return $this->service->getAll($params);
    }

    #[OA\Post(
        path: '/tag',
        description: 'Создание нового тэга ',
        summary: 'Создаёт новый тэг',
        tags: ['Tag']
    )]
    #[OA\RequestBody(
        description: 'Данные для создания',
        required: true,
        content: new OA\JsonContent(
            required: ['title',],
            properties: [
                new OA\Property(property: 'title', type: 'string', example: 'Test'),
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Успешное удаление',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'id', type: 'integer', example: 1),
                new OA\Property(property: 'title', type: 'string', example: 'testTag'),
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
                new OA\Property(property: 'errors', type: 'object', example: ['title' => 'Не может быть пустым'],)
            ],
            type: 'object'
        )
    )]
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

    #[OA\Put(
        path: '/tag/{id}',
        description: 'Изменяет  тэг ',
        summary: 'Изменяет  тэг',
        tags: ['Tag'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Id тэга',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ]
    )]
    #[OA\RequestBody(
        description: 'Данные для редактирования',
        required: true,
        content: new OA\JsonContent(
            required: ['title',],
            properties: [
                new OA\Property(property: 'title', type: 'string', example: 'Test'),
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Успешное изменение',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'id', type: 'integer', example: 1),
                new OA\Property(property: 'message', type: 'string', example: 'Тэг успешно изменён'),
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
                new OA\Property(property: 'errors', type: 'object', example: ['title' => 'Не может быть пустым'],)
            ],
            type: 'object'
        )
    )]
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
                'message' => Yii::t('app', 'tag_updated_successfully'),
            ];

        }
        return [
            'success' => false,
            'errors' => $form->getErrors(),
        ];
    }

    #[OA\Delete(
        path: '/tag/{id}',
        description: 'Удаляет  тэг ',
        summary: 'Удаляет  тэг',
        tags: ['Tag'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Id тэга',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ]
    )]
    #[OA\Response(
        response: 200,
        description: 'Успешное удаление',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'id', type: 'integer', example: 1),
                new OA\Property(property: 'message', type: 'string', example: 'Тэг успешно удалён'),
            ],
            type: 'object'
        )
    )]
    public function actionDelete($id): array
    {
        $this->service->remove($id);
        return [
            'success' => true,
            'message' => Yii::t('app', 'tag_deleted_successfully'),
        ];
    }
}