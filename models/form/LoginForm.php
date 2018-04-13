<?php

namespace app\models\form;

use app\models\data\UserData;
use app\models\repository\UserRepository;
use yii\base\Model;

class LoginForm extends Model
{
    /**
     * @var string
     */
    public $identifier = '';

    /**
     * @var string
     */
    public $password = '';

    /**
     * @var UserData
     */
    public $userData;

    public function rules()
    {
        return [
            [['identifier', 'password'], 'required'],
            [['identifier', 'password'], 'string'],
            [['identifier'], 'checkForUser']
        ];
    }

    /**
     * @param string $attribute
     * @return boolean
     */
    public function checkForUser($attribute)
    {
        /* @var $repo UserRepository */
        $repo = make(UserRepository::class);
        if (!$userData = $repo->getByIdentifierAndPassword($this->identifier, UserData::generateSaltedPassword($this->password))) {
            $this->addError($attribute, 'Wrong username or password');
            return false;
        }
        $this->userData = $userData;
        return true;
    }
}