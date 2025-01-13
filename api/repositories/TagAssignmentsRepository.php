<?php

namespace api\repositories;

use api\interface\repositories\TagAssignmentsRepositoryInterface;
use api\models\TagAssignments;
use Yii;
use yii\db\StaleObjectException;
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
            throw new \RuntimeException(Yii::t('app', 'save_error'));
        }
    }

    /**
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function detach(TagAssignments $assignments): void
    {
        if (!$assignments->delete()) {
            throw new \RuntimeException(Yii::t('app', 'remove_error'));
        }
    }

    /**
     * @throws NotFoundHttpException
     */
    public function getByCondition($condition): TagAssignments
    {
        if (!$assignments = TagAssignments::findOne($condition)) {
            throw new NotFoundHttpException(Yii::t('app', 'assignment_not_found'));
        }
        return $assignments;
    }
}