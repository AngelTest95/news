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
        ['label' => 'Home', 'url' => Url::to(['/index'])],
        ['label' => 'Login', 'options' => [
            'class' => 'nav-buttons btn dialog-button',
            'id' => 'login-button',
            'data-url' => Url::to(['/index/login'])
        ]],
        ['label' => 'Register', 'options' => [
            'class' => 'nav-buttons btn dialog-button',
            'id' => 'register-button',
            'data-url' => Url::to(['/index/register'])
        ]],
    ],
]);
NavBar::end();