<?php
use Codeception\Specify;
use Prophecy\Argument;
use app\models\data\UserData;
use app\models\domain\Register;
use app\models\data\MailQueueData;
use app\models\data\VerificationLinkData;
use app\models\domain\exception\UnableToSaveException;

class RegisterTest extends \Codeception\Test\Unit
{
    use Specify;

    public function testRegister()
    {
        $this->specify('User data gets saved, verification link is saved, mail is saved to queue', function () {
            $this->setUserMock(true);
            $this->setVerificationLinkMock(1, true);
            $this->setMailQueueMock(1, true);
            make(Register::class)->register();
        });

        $this->specify('User data doesn\'t get saved. Exception thrown', function () {
            $this->setUserMock(false);
            $this->setVerificationLinkMock(0, true);
            $this->setMailQueueMock(0, true);
            make(Register::class)->register();
        }, ['throws' => UnableToSaveException::class]);

        $this->specify('User data gets saved. Verification link isn\'t saved Exception thrown', function () {
            $this->setUserMock(true);
            $this->setVerificationLinkMock(1, false);
            $this->setMailQueueMock(0, true);
            make(Register::class)->register();
        }, ['throws' => UnableToSaveException::class]);

        $this->specify('User data gets saved. Verification link is saved. Mail is not saved. Exception thrown', function () {
            $this->setUserMock(true);
            $this->setVerificationLinkMock(1, true);
            $this->setMailQueueMock(1, false);
            make(Register::class)->register();
        }, ['throws' => UnableToSaveException::class]);
    }

    public function testGenerateVerificationToken()
    {
        $this->specify('Properly generates verification URL', function () {
            $email = 'test@google.com';
            expect(make(Register::class, [], ['email' => $email])->generateVerificationLink())->equals(\yii\helpers\Url::to([
                '/verification',
                'token' => sha1(Register::VERIFICATION_SALT . $email)
            ], true));
        });
    }

    private function setUserMock($shouldSave)
    {
        $userData = $this->prophesize(UserData::class);
        $userData->save()->shouldBeCalledTimes(1)->willReturn($shouldSave);
        $userData->hasAttribute(Argument::any())->willReturn(true);
        bind(UserData::class, $userData->reveal());
    }

    private function setVerificationLinkMock($shouldBeCalled, $willReturn)
    {
        $verificationLinkData = $this->prophesize(VerificationLinkData::class);
        $verificationLinkData->save()->shouldBeCalledTimes($shouldBeCalled)->willReturn($willReturn);
        $verificationLinkData->hasAttribute(Argument::any())->willReturn(true);
        bind(VerificationLinkData::class, $verificationLinkData->reveal());
    }

    private function setMailQueueMock($shouldBeCalled, $willReturn)
    {
        $mailQueueData = $this->prophesize(MailQueueData::class);
        $mailQueueData->save()->shouldBeCalledTimes($shouldBeCalled)->willReturn($willReturn);
        $mailQueueData->hasAttribute(Argument::any())->willReturn(true);
        bind(MailQueueData::class, $mailQueueData->reveal());
    }
}