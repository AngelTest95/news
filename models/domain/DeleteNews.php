<?php

namespace app\models\domain;

use app\models\data\NewsData;
use app\models\repository\NewsRepository;
use yii\base\Model;

class DeleteNews extends Model
{
    public $newsId;

    /**
     * Saves a piece of news and related images
     */
    public function delete()
    {
        /* @var NewsData $newsData*/
        $newsData = make(NewsRepository::class)->getById($this->newsId);
        $newsData->delete();
    }
}