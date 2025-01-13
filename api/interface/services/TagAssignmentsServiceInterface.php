<?php

namespace api\interface\services;

use api\forms\TagAssignmentsForm;

interface TagAssignmentsServiceInterface
{
    public function attachTag(TagAssignmentsForm $form): void;


    public function detachTag(TagAssignmentsForm $form): void;

}