<?php

namespace api\services;

use api\forms\TagAssignmentsForm;
use api\interface\repositories\TagAssignmentsRepositoryInterface;
use api\interface\services\TagAssignmentsServiceInterface;
use api\models\TagAssignments;
use Throwable;

class TagAssignmentsService implements TagAssignmentsServiceInterface
{

    private TagAssignmentsRepositoryInterface $repository;

    public function __construct(TagAssignmentsRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function attachTag(TagAssignmentsForm $form): void
    {
        $assigment = TagAssignments::create(
            $form->id_tag,
            $form->id_post
        );
        $this->repository->save($assigment);
    }

    /**
     * @throws Throwable
     */
    public function detachTag(TagAssignmentsForm $form): void
    {
        $assignments = $this->repository->getByCondition(['id_post' => $form->id_post, 'id_tag' => $form->id_tag]);
        $this->repository->detach($assignments);

    }
}