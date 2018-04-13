<?php

namespace app\models\domain;

use app\models\data\MailQueueData;
use app\models\data\UserData;
use app\models\data\VerificationLinkData;
use app\models\domain\exception\UnableToSaveException;
use yii\base\Model;
use yii\helpers\Url;
use yii\swiftmailer\Mailer;

class Register extends Model
{
    const VERIFICATION_SALT = 'jo1h23e120ihe120iph';

    /**
     * @var string
     */
    public $email;

    /**
     * @var UserData
     */
    private $userData;

    /**
     * Saves a user. Saves a verification link. Sends a verifications email.
     */
    public function register()
    {
        $this->saveUserData();
        $this->saveVerificationLink($this->userData->id);
        $this->sendVerificationEmail();
    }

    /**
     * Saves a user
     * @throws UnableToSaveException
     */
    private function saveUserData()
    {
        /* @var $userData UserData */
        $userData = make(UserData::class);
        $userData->email = $this->email;
        if (!$userData->save()) {
            throw new UnableToSaveException('Unable to save user');
        }
        $this->userData = $userData;
    }

    /**
     * @return UserData
     */
    public function getNewlyRegisteredUser()
    {
        return $this->userData;
    }

    /**
     * @param integer $userId
     * @throws UnableToSaveException
     */
    private function saveVerificationLink($userId)
    {
        /* @var $verificationLinkData VerificationLinkData */
        $verificationLinkData = make(VerificationLinkData::class);
        $verificationLinkData->user_id = $userId;
        $verificationLinkData->verification_token = $this->generateVerificationToken();
        if (!$verificationLinkData->save()) {
            throw new UnableToSaveException('Unable to save verification link');
        }
    }

    /**
     * Sends a verification email to the user
     * @throws UnableToSaveException
     */
    private function sendVerificationEmail()
    {
        $textBody = 'In order to confirm your email please click on this link: ' . $this->generateVerificationLink();
        /* @var $mailer Mailer */
        $mailer = make(Mailer::class)->compose()
            ->setSubject('Verification link')
            ->setHtmlBody($textBody);
        /* @var $mailQueueData MailQueueData */
        $mailQueueData = make(MailQueueData::class);
        $mailQueueData->from = 'angel@news.com';
        $mailQueueData->subject = $mailer->getSubject();
        $mailQueueData->body = $textBody; //We should've used something along the lines of: $mailer->getSwiftMessage()->toString(); But for some reason I can't get the links to work properly with it
        $mailQueueData->recipient = $this->email;
        if(!$mailQueueData->save()) {
            throw new UnableToSaveException('Unable to save mail queue');
        }
    }

    /**
     * Generates the link for verifying the user account
     * @return string
     */
    public function generateVerificationLink()
    {
        return Url::to(['/verification', 'token' => $this->generateVerificationToken()], true);
    }

    /**
     * Generates a verification token based on the email
     * @return string
     */
    private function generateVerificationToken()
    {
        return sha1(self::VERIFICATION_SALT . $this->email);
    }
}