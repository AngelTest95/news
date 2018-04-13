<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $news \app\models\data\NewsData */
?>
<div>
    <?=Html::button('Add news', [
        'class' => 'btn btn-primary',
        'data-url' => Url::to(['my-news/add']),
        'id' => 'add-news-button'
    ])?>
</div>
<br/>
<div id="add-news-dialog"></div>
<?php
foreach ($news as $article) {
    echo $this->render('/common/news-article', ['news' => $article]);
}
?>
<?php $this->registerJsFile('@web/js/my-news.js', ['depends' => \yii\web\JqueryAsset::className()]); ?>