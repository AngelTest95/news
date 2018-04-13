<?php

namespace app\models\domain;

use app\models\data\UserData;
use app\models\repository\UserRepository;
use yii\base\Model;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;

class User extends Model implements IdentityInterface
{
    /**
     * @var UserData
     */
    public $userData;

    /**
     * User constructor.
     * @param UserData $userData
     * @param array $config
     * @throws \Exception
     */
    public function __construct(UserData $userData, array $config = [])
    {
        if (!$userData) {
            throw new \Exception('User not found');
        }

        $this->userData = $userData;
        parent::__construct($config);
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->userData->username;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->userData->email;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        $userData = make(UserRepository::class)->getById($id);
        return $userData ? new static($userData) : null;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('This is not supported');
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->userData->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        throw new NotSupportedException('Auth key is not used in this project');
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        throw new NotSupportedException('Auth key is not used in this project');
    }

    /**
     * @return boolean
     */
    public function getIsGuest()
    {
        return false;
    }
}