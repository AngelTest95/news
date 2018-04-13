<?php
use Codeception\Specify;
use app\models\domain\exception\UnableToSaveException;
use Prophecy\Argument;
use app\models\data\NewsData;
use app\models\domain\SaveNews;
use app\models\data\Image2newsTableData;

class SaveNewsTest extends \Codeception\Test\Unit
{
    use Specify;

    public function testSave()
    {
        $builderConfig = [
            'title' => 'test',
            'content' => 'testContent',
            'images' => [
                'test.jpg'
            ]
        ];

        $this->specify('Saves news and related images successfully', function () use ($builderConfig) {
            $this->setNewsMock(true);
            $this->setImage2NewsDataMock(true, 1);
            make(SaveNews::class, [], $builderConfig)->save();
        });

        $this->specify('Not saving the news throws an exception', function () use ($builderConfig) {
            $this->setNewsMock(false);
            $this->setImage2NewsDataMock(true, 0);
            make(SaveNews::class, [], $builderConfig)->save();
        }, ['throws' => UnableToSaveException::class]);

        $this->specify('Not saving the images throws an exception', function () use ($builderConfig) {
            $this->setNewsMock(true);
            $this->setImage2NewsDataMock(false, 1);
            make(SaveNews::class, [], $builderConfig)->save();
        }, ['throws' => UnableToSaveException::class]);
    }

    private function setNewsMock($shouldSave)
    {
        $newsData = $this->prophesize(NewsData::class);
        $newsData->save()->shouldBeCalledTimes(1)->willReturn($shouldSave);
        $newsData->hasAttribute(Argument::any())->willReturn(true);
        bind(NewsData::class, $newsData->reveal());
    }

    private function setImage2NewsDataMock($shouldSave, $shouldBeCalledTimes)
    {
        $images2NewsMock = $this->prophesize(Image2newsTableData::class);
        $images2NewsMock->hasAttribute(Argument::any())->willReturn(true);
        $images2NewsMock->save()->shouldBeCalledTimes($shouldBeCalledTimes)->willReturn($shouldSave);
        bind(Image2newsTableData::class, $images2NewsMock->reveal());
    }
}