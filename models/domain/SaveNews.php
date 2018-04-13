<?php

namespace app\models\domain;

use app\models\data\Image2newsTableData;
use app\models\data\NewsData;
use app\models\domain\exception\UnableToSaveException;
use yii\base\Model;

class SaveNews extends Model
{
    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $content;

    /**
     * @var array
     */
    public $images;

    /**
     * @var integer
     */
    public $userId;

    /**
     * Saves a piece of news and related images
     * @throws UnableToSaveException
     */
    public function save()
    {
        /* @var NewsData $newsData*/
        $newsData = make(NewsData::class);
        $newsData->title = $this->title;
        $newsData->content = $this->content;
        $newsData->created_at = date('Y-m-d H:i:s');
        $newsData->user_id = $this->userId;
        if (!$newsData->save()) {
            throw new UnableToSaveException('Unable to save news');
        }
        $this->saveImages($newsData->id);
    }

    /**
     * @param integer $newsId
     * @throws UnableToSaveException
     */
    private function saveImages($newsId)
    {
        foreach ($this->images as $image) {
            /* @var $image2newsTableData Image2newsTableData*/
            $image2newsTableData = make(Image2newsTableData::class);
            $image2newsTableData->news_id = $newsId;
            $image2newsTableData->image = $image;
            if (!$image2newsTableData->save()) {
                throw new UnableToSaveException('Unable to save image');
            }
        }
    }
}