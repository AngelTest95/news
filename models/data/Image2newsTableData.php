<?php

namespace app\models\data;

use Yii;

/**
 * This is the model class for table "image2news_table".
 *
 * @property int $id
 * @property int $news_id
 * @property string $image
 *
 * @property NewsData $news
 */
class Image2newsTableData extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'image2news_table';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['news_id'], 'required'],
            [['news_id'], 'integer'],
            [['image'], 'string', 'max' => 200],
            [['news_id'], 'exist', 'skipOnError' => true, 'targetClass' => NewsData::className(), 'targetAttribute' => ['news_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'news_id' => 'News ID',
            'image' => 'Image',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasOne(NewsData::className(), ['id' => 'news_id']);
    }
}
