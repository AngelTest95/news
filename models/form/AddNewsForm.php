<?php

namespace app\models\form;

use app\models\data\UserData;
use app\models\domain\Register;
use app\models\domain\SaveNews;
use app\models\repository\UserRepository;
use yii\base\Model;

class AddNewsForm extends Model
{
    /**
     * @var string
     */
    public $content = '';

    /**
     * @var string
     */
    public $title = '';

    /**
     * @var array
     */
    public $imageFiles = [];

    /**
     * @var array
     */
    private $imageNames = [];

    /**
     * @var integer
     */
    public $userId;

    public function rules()
    {
        return [
            [['userId'], 'integer'],
            [['content', 'title', 'userId'], 'required'],
            [['content'], 'string', 'max' => 10000, 'min' => 15],
            [['title'], 'string', 'max' => 80, 'min' => 10],
            [['imageFiles'], 'image', 'skipOnEmpty' => false, 'extensions' => 'png, jpg', 'maxFiles' => 4],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            foreach ($this->imageFiles as $file) {
                $fileName = 'uploads/' . md5($file->baseName . time()) . '.' . $file->extension;
                $file->saveAs($fileName, false);
                $this->imageNames[] = $fileName;
            }
            return true;
        } else {
            return false;
        }
    }

    public function save()
    {
        make(SaveNews::class, [], [
            'title' => $this->title,
            'content' => $this->content,
            'images' => $this->imageNames,
            'userId' => $this->userId
        ])->save();
    }
}
