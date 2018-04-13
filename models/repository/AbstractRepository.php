<?php

namespace app\models\repository;

use yii\base\BaseObject;
use yii\db\ActiveQuery;

abstract class AbstractRepository extends BaseObject
{
    const ALL = 'all';
    const ONE = 'one';
    const COLUMN = 'column';
    const SCALAR = 'scalar';
    const EXISTS = 'exists';
    const COUNT = 'count';

    /**
     * @param ActiveQuery $query
     * @param string $returnMethod
     * @param boolean $asArray
     * @return mixed
     */
    protected function build($query, $returnMethod, $asArray = false)
    {
        if ($asArray) {
            $query->asArray();
        }

        return $query->$returnMethod();
    }
}