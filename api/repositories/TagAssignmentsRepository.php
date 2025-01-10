<?php

namespace api\repositories;

use api\interface\repositories\TagAssignmentsRepositoryInterface;
use api\models\TagAssignments;
use yii\web\NotFoundHttpException;

class TagAssignmentsRepository implements TagAssignmentsRepositoryInterface
{
    public function save(TagAssignments $assignments): void
    {
        try {
            if ($assignments->validate()) {
                $assignments->save();
            }

        } catch (\Exception $e) {
            throw new \RuntimeException('Saving error.');
        }
    }

    /**
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function detach(TagAssignments $assignments): void
    {
        if (!$assignments->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }

    /**
     * @throws NotFoundHttpException
     */
    public function getByCondition($condition): TagAssignments
    {
        if (!$assignments = TagAssignments::findOne($condition)) {
            throw new NotFoundHttpException('Assigment is not found.');
        }
        return $assignments;
    }
}