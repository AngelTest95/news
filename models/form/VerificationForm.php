<?php

namespace app\models\form;

use app\models\data\UserData;
use app\models\data\VerificationLinkData;
use app\models\domain\Verification;
use app\models\repository\UserRepository;
use app\models\repository\VerificationLinkRepository;
use yii\base\Model;

class VerificationForm extends Model
{
    /**
     * @var string
     */
    public $token = '';

    /**
     * @var string
     */
    public $username = '';

    /**
     * @var string
     */
    public $password = '';

    public function rules()
    {
        return [
            [['token'], 'string', 'max' => 40, 'min' => 40],
            [['username', 'password'], 'required'],
            [['username', 'password'], 'string', 'max' => 20, 'min' => 4],
            ['username','match','pattern' => '/^[a-zA-Z0-9]*$/', 'message' => 'Only letters and numbers are allowed in usernames'],
            [['username'], function ($attribute) {
                /* @var $repo UserRepository */
                $repo = make(UserRepository::class);
                if ($repo->usernameExists($this->username)) {
                    $this->addError($attribute, 'This username is already taken');
                }
            }],
            [['token'], function($attribute) {
                if (!make(VerificationLinkRepository::class)->getByToken($this->token)) {
                    $this->addError($attribute, 'Wrong token');
                }
            }]
        ];
    }

    /**
     * Verifies the user, sets the new email and password and returns him
     * @return UserData
     */
    public function save()
    {
        /* @var $verification Verification */
        $verification = make(Verification::class, [], [
            'username' => $this->username,
            'token' => $this->token,
            'password' => $this->password,
        ]);
        $verification->save();
        return $verification->getUserData();
    }
}