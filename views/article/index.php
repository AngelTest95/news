<?php
use yii\helpers\Url;
use branchonline\lightbox\Lightbox;
use yii\helpers\Html;
/* @var $news \app\models\data\NewsData*/
?>
<div class="col-lg-12 well">
    <div class="col-lg-12">
        <div class="col-lg-5 author-holder">Published by <?=$news->user->username?> <?=$news->created_at?></div>
    </div>
    <article class="col-lg-12">
        <h2><?=Html::encode($news->title)?></h2>
    </article>
    <article class="col-lg-12">
        <?=Html::encode($news->content)?>
    </article>
    <div class="col-lg-12" style="margin-top:30px">
        <?php foreach ($news->images as $image) {?>
            <div class="col-lg-3 no-padding-left">
            <?php
                echo Lightbox::widget([
                    'files' => [
                        [
                            'thumbOptions' => ['style' => 'height:150px; width:100%'],
                            'thumb' => $image->image,
                            'original' => $image->image,
                        ],
                    ]
                ]);?>
            </div>
        <?php }?>
    </div>
    <div class="pull-right">
        <?php if ($news->user_id == Yii::$app->user->getId()) {?>
            <input type="button" class="btn btn-danger delete-news-button" value="Delete" data-url="<?=Url::to(['/my-news/delete', 'id' => $news->id])?>"/>
        <?php }?>
    </div>
</div>