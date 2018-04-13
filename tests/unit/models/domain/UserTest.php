<?php
use Codeception\Specify;
use app\models\domain\exception\UnableToSaveException;
use app\models\data\UserData;
use app\models\domain\User;

class UserTest extends \Codeception\Test\Unit
{
    use Specify;

    public function testConstruct()
    {
        $this->specify('The constructor throws an error when not provided with a userData', function () {
            new User();
        }, ['throws' => \yii\base\ErrorException::class]);

        $this->specify('The constructor sets the userData', function () {
            $userData = make(UserData::class);
            $user = make(User::class, [$userData]);
            expect($userData)->equals($user->userData);
        });
    }

    public function testGetUsername()
    {
        $this->specify('Returns username', function () {
            /* @var $userData UserData*/
            $userData = make(UserData::class);
            $userName = 'test';
            $userData->username = $userName;
            /* @var $user User*/
            $user = make(User::class, [$userData]);
            expect($user->getUsername())->equals($userName);
        });
    }

    public function testGetEmail()
    {
        $this->specify('Returns email', function () {
            /* @var $userData UserData*/
            $userData = make(UserData::class);
            $email = 'test@test.com';
            $userData->email = $email;
            /* @var $user User*/
            $user = make(User::class, [$userData]);
            expect($user->getEmail())->equals($email);
        });
    }

    public function testFindIdentityByAccessToken()
    {
        $this->specify('Throws error', function () {
            /* @var $userData UserData*/
            $userData = make(UserData::class);
            /* @var $user User*/
            $user = make(User::class, [$userData]);
            $user->findIdentityByAccessToken('13');
        }, ['throws' => \yii\base\NotSupportedException::class]);
    }

    public function testGetId()
    {
        $this->specify('Returns id', function () {
            /* @var $userData UserData*/
            $userData = make(UserData::class);
            $id = 1;
            $userData->id = $id;
            /* @var $user User*/
            $user = make(User::class, [$userData]);
            expect($user->getId())->equals($id);
        });
    }

    public function testGetAuthKey()
    {
        $this->specify('Throws error', function () {
            /* @var $userData UserData*/
            $userData = make(UserData::class);
            /* @var $user User*/
            $user = make(User::class, [$userData]);
            $user->getAuthKey();
        }, ['throws' => \yii\base\NotSupportedException::class]);
    }

    public function testValidateAuthKey()
    {
        $this->specify('Throws error', function () {
            /* @var $userData UserData*/
            $userData = make(UserData::class);
            /* @var $user User*/
            $user = make(User::class, [$userData]);
            $user->validateAuthKey('13');
        }, ['throws' => \yii\base\NotSupportedException::class]);
    }

    public function testGetIsGuest()
    {
        $this->specify('Logged user is not a guest', function () {
            /* @var $userData UserData*/
            $userData = make(UserData::class);
            /* @var $user User*/
            $user = make(User::class, [$userData]);
            expect($user->getIsGuest())->equals(false);
        });
    }
}