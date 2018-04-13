<?php
use Codeception\Specify;
use app\models\domain\exception\UnableToSaveException;
use Prophecy\Argument;

class VerificationTest extends \Codeception\Test\Unit
{
    use Specify;

    public function testSave()
    {
        $this->specify('Saves the user password and username. Deletes verification link', function () {
            $this->mockVerificationLinkRepo(1, true);
            make(\app\models\domain\Verification::class)->save();
        });

        $this->specify('Fails to save Saves the user password and username. Throws exception', function () {
            $this->mockVerificationLinkRepo(0, false);
            make(\app\models\domain\Verification::class)->save();
        }, ['throws' => UnableToSaveException::class]);
    }

    private function mockVerificationLinkRepo($deleteCalls, $shouldSave)
    {
        $verificationLinkRepo = $this->prophesize(\app\models\repository\VerificationLinkRepository::class);
        $verificationLinkData = $this->prophesize(\app\models\data\VerificationLinkData::class);
        $verificationLinkData->delete()->shouldBeCalledTimes($deleteCalls);
        $userData = $this->prophesize(\app\models\data\UserData::class);
        $userData->hasAttribute(Argument::any())->willReturn(true);
        $userData->save()->shouldBeCalledTimes(1)->willReturn($shouldSave);
        $verificationLinkData->hasAttribute(Argument::any())->willReturn(true);
        $verificationLinkData->user = $userData->reveal();
        $verificationLinkRepo->getByToken(Argument::any())->willReturn($verificationLinkData->reveal());
        bind(\app\models\repository\VerificationLinkRepository::class, $verificationLinkRepo->reveal());
    }
}