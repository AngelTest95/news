<?php
foreach ($news as $article) {
    echo $this->render('/common/news-article', ['news' => $article]);
}

// display pagination
echo \yii\widgets\LinkPager::widget([
    'pagination' => $pagination,
]);