<?php
use Codeception\Specify;
use app\models\repository\NewsRepository;
use Prophecy\Argument;
use app\models\data\NewsData;
use app\models\domain\DeleteNews;

class DeleteNewsTest extends \Codeception\Test\Unit
{
    use Specify;

    public function testDelete()
    {
        $this->specify('Repository gets called, news get returned and deleted', function () {
            $this->setNewsRepo();
            make(DeleteNews::class)->delete();
        });
    }

    private function setNewsRepo()
    {
        $repoProphecy = $this->prophesize(NewsRepository::class);
        $repoProphecy->getById(Argument::any())->willReturn($this->getNewsMock());
        bind(NewsRepository::class, $repoProphecy->reveal());
    }

    private function getNewsMock()
    {
        $newsData = $this->prophesize(NewsData::class);
        $newsData->delete()->shouldBeCalledTimes(1);
        return $newsData->reveal();
    }
}