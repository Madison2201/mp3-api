<?php

namespace api\interface\repositories;

use api\models\TagAssignments;

interface TagAssignmentsRepositoryInterface
{
    public function save(TagAssignments $assignments): void;


    public function detach(TagAssignments $assignments): void;


    public function getByCondition($condition): TagAssignments;

}