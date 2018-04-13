<?php
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use yii\helpers\Url;

NavBar::begin([
    'brandLabel' => 'Angel News',
    'brandUrl' => Url::to(['/index']),
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top',
    ],
]);
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => [
        ['label' => 'Logged as: ' . Yii::$app->user->identity->getUsername(), 'url' => Url::to(['/index'])],
        ['label' => 'Home', 'url' => Url::to(['/index'])],
        ['label' => 'My news', 'url' => Url::to(['/my-news'])],
        ['label' => 'Logout', 'url' => Url::to(['/index/logout-user']), 'options' => [
            'class' => 'nav-buttons btn',
        ]],
    ],
]);
NavBar::end();