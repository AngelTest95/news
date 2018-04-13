<?php

namespace app\models\domain;

use app\models\data\UserData;
use app\models\data\VerificationLinkData;
use app\models\domain\exception\UnableToSaveException;
use app\models\repository\VerificationLinkRepository;
use yii\base\Model;

class Verification extends Model
{
    /**
     * @var integer
     */
    public $token;

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $password;

    /**
     * @var VerificationLinkData
     */
    private $verificationLinkData;

    /**
     * @var UserData
     */
    private $userData;

    /**
     * Verify a user and save the new username and password
     */
    public function save()
    {
        $this->setVerificationLinkData();
        $this->setUserData();
        $this->updateUser();
        $this->deleteVerificationLinkData();
    }

    /**
     * Saves the username and password
     * @throws UnableToSaveException
     */
    private function updateUser()
    {
        $this->userData->password = UserData::generateSaltedPassword($this->password);
        $this->userData->username = $this->username;
        if (!$this->userData->save()) {
            throw new UnableToSaveException('Unable to save username or password');
        }
    }

    /**
     * Sets the verificationLinkData
     */
    private function setVerificationLinkData()
    {
        $this->verificationLinkData = make(VerificationLinkRepository::class)->getByToken($this->token);
    }

    /**
     * Deletes the verificationLinkData
     */
    private function deleteVerificationLinkData()
    {
        $this->verificationLinkData->delete();
    }

    /**
     * Sets the user Data
     */
    private function setUserData()
    {
        $this->userData = $this->verificationLinkData->user;
    }

    /**
     * @return UserData
     */
    public function getUserData()
    {
        return $this->userData;
    }
}