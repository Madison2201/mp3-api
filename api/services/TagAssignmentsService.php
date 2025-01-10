<?php

namespace api\services;

use api\forms\TagAssignmentsForm;
use api\interface\repositories\TagAssignmentsRepositoryInterface;
use api\interface\services\TagAssignmentsServiceInterface;
use api\models\TagAssignments;
use api\repositories\TagAssignmentsRepository;

class TagAssignmentsService implements TagAssignmentsServiceInterface
{

    private TagAssignmentsRepositoryInterface $repository;

    public function __construct(TagAssignmentsRepositoryInterface $repository){
        $this->repository = $repository;
    }
    public function attachTag(TagAssignmentsForm $form)
    {
        $assigment = TagAssignments::create(
            $form->id_tag,
            $form->id_post
        );
        $this->repository->save($assigment);
    }

    public function detachTag(TagAssignmentsForm $form){
        $assignments = $this->repository->getByCondition(['id_post' => $form->id_post, 'id_tag' => $form->id_tag]);
        $this->repository->detach($assignments);

    }
}