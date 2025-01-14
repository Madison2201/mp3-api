<?php

namespace api\controllers;

use api\components\JwtAuth;
use api\forms\PostForm;
use api\helpers\FileHelper;
use api\interface\services\PostServiceInterface;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\rest\Controller;
use yii\web\Response;
use yii\web\UploadedFile;
use OpenApi\Attributes as OA;

class PostController extends Controller
{

    private PostServiceInterface $service;

    public function __construct($id, $module, PostServiceInterface $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
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
                        'index' => ['get'],
                        'create' => ['post'],
                        'update' => ['put'],
                        'delete' => ['delete'],
                    ],
                ],
            ]
        );
    }

    #[OA\Get(
        path: '/post',
        description: 'Возвращает список всех постов с возможностью фильтрации',
        summary: 'Получить список всех постов',
        tags: ['Posts'],
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
            new OA\Parameter(
                name: 'expand',
                description: 'Расширение запроса',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', example: ['tags', 'files'])
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
                new OA\Property(property: 'description', type: 'string', example: 'Пост успешно обновлен'),
                new OA\Property(property: 'status', type: 'integer', example: 1),
                new OA\Property(property: 'created_at', type: 'string', example: "2025-01-13 11:40:00"),
            ],
            type: 'object'
        )
    )]
    public function actionIndex(): ActiveDataProvider
    {
        $params = Yii::$app->request->queryParams;
        return $this->service->getPosts($params);
    }

    #[OA\Post(
        path: '/post',
        description: 'Создает новый пост на основе переданных данных.',
        summary: 'Создать новый пост',
        tags: ['Posts'],
    )]
    #[OA\RequestBody(
        description: 'Данные для создания',
        required: true,
        content: new OA\JsonContent(
            required: ['title', 'description', 'files'],
            properties: [
                new OA\Property(property: 'title', type: 'string', example: 'Test'),
                new OA\Property(property: 'description', type: 'string', example: 'TestDescription'),
                new OA\Property(property: 'file', type: 'mp3', example: 'test.mp3'),
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Успешное создание',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'id', type: 'integer', example: 1),
                new OA\Property(property: 'title', type: 'string', example: 'Test'),
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

    #[OA\Put(
        path: '/post/{id}',
        description: 'Редактирует  пост на основе переданных данных.',
        summary: 'Редактирует новый пост',
        tags: ['Posts'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Id поста',
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
            required: ['title', 'description', 'files'],
            properties: [
                new OA\Property(property: 'title', type: 'string', example: 'Test'),
                new OA\Property(property: 'description', type: 'string', example: 'TestDescription'),
                new OA\Property(property: 'file', type: 'mp3', example: 'test.mp3'),
                new OA\Property(property: 'status', type: 'integer', example: 2),
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Успешное редактирование',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'id', type: 'integer', example: 1),
                new OA\Property(property: 'message', type: 'string', example: 'Пост успешно изменён'),
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
    #[OA\Response(
        response: 403,
        description: 'Ошибка доступа',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'integer', example: 403),
                new OA\Property(property: 'message', type: 'string', example: 'Вы не можете производить данное действие'),
            ],
            type: 'object'
        )
    )]
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
                'message' => Yii::t('app', 'post_updated_successfully'),
            ];

        }
        return [
            'success' => false,
            'errors' => $form->getErrors(),
        ];

    }

    #[OA\Delete(
        path: '/post/{id}',
        description: 'Удаляет  пост ',
        summary: 'Удаляет новый пост',
        tags: ['Posts'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Id поста',
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
                new OA\Property(property: 'message', type: 'string', example: 'Пост успешно удалён'),
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 403,
        description: 'Ошибка доступа',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'integer', example: 403),
                new OA\Property(property: 'message', type: 'string', example: 'Вы не можете производить данное действие'),
            ],
            type: 'object'
        )
    )]
    public function actionDelete(int $id): Response|array
    {
        $this->service->remove($id);
        return [
            'success' => true,
            'message' => Yii::t('app', 'post_deleted_successfully'),
        ];
    }
}