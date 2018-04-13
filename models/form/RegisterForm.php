<?php

namespace app\models\form;

use app\models\domain\Register;
use app\models\repository\UserRepository;
use yii\base\Model;

class RegisterForm extends Model
{
    /**
     * @var string
     */
    public $email = '';

    public function rules()
    {
        return [
            [['email'], 'required'],
            [['email'], 'string', 'max' => 40, 'min' => 4],
            [['email'], 'email'],
            [['email'], function($attribute) {
                //@TODO Perhaps if the email is already used, but not validated we should resend the validation email?
                /* @var $repo UserRepository */
                $repo = make(UserRepository::class);
                if ($repo->emailExists($this->email)) {
                    $this->addError($attribute, 'This email is already taken');
                }
                return true;
            }]
        ];
    }

    /**
     * Registers a user with the provided information and returns him
     * @return \app\models\data\UserData
     */
    public function save()
    {
        /* @var $register Register */
        $register = make(Register::class, [], ['email' => $this->email]);
        $register->register();
        return $register->getNewlyRegisteredUser();
    }
}