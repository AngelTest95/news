<?php

namespace app\models\repository;

use app\models\data\NewsData;

class NewsRepository extends AbstractRepository
{
    /**
     * @param integer $offset
     * @param integer $limit
     * @return array
     */
    public function getAllNews($offset = 0, $limit = 0)
    {
        $query = NewsData::find()->orderBy('id DESC');
        if ($offset) {
            $query->offset($offset);
        }
        if ($limit) {
            $query->limit($limit);
        }
        return $this->build($query, self::ALL);
    }

    /**
     * @return integer
     */
    public function getNewsCount()
    {
        $query = NewsData::find();
        return $this->build($query, self::COUNT);
    }

    /**
     * @param integer $userId
     * @return array
     */
    public function getAllNewsByUserId($userId)
    {
        $query = NewsData::find()->where(['user_id' => $userId])->orderBy('id DESC');
        return $this->build($query, self::ALL);
    }

    /**
     * @param $id
     * @return NewsData
     */
    public function getById($id)
    {
        $query = NewsData::find()->where(['id' => $id]);
        return $this->build($query, self::ONE);
    }
}