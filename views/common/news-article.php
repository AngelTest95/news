<?php
use yii\helpers\Url;
use yii\helpers\Html;
/* @var $news \app\models\data\NewsData*/
?>
<div class="col-lg-12 well">
    <div class="col-lg-12">
        <div class="col-lg-4 author-holder">Published by <?=$news->user->username?> <?=$news->created_at?></div>
    </div>
    <div class="col-lg-12">
        <a href="<?=Url::to(['/article', 'id' => $news->id])?>">
            <div class="col-lg-3"><img style='height:150px; width:100%' src="<?=Url::to([$news->images[0]->image])?>">
            </div>
        </a>
        <div class="col-lg-9">
            <h3 class="col-lg-12"><?=Html::encode($news->title)?></h3>
            <br/>
            <div>
                <article class="col-lg-12">
                    <?=strlen($news->content) > 50 ? substr(Html::encode($news->content), 0, 300) . "..." : Html::encode($news->content);?>
                </article>
            </div>
        </div>
    </div>
    <div class="pull-right">
        <?php if ($news->user_id == Yii::$app->user->getId()) {?>
            <input type="button" class="btn btn-danger delete-news-button" value="Delete" data-url="<?=Url::to(['/my-news/delete', 'id' => $news->id])?>"/>
        <?php }?>
        <a href="<?=Url::to(['/article', 'id' => $news->id])?>">Read article</a>
    </div>
</div>