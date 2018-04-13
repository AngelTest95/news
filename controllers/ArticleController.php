<?php

namespace app\controllers;

use app\models\repository\NewsRepository;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ArticleController extends Controller
{
    public function actionIndex($id)
    {
        if (!$newsData = make(NewsRepository::class)->getById($id)) {
            throw new NotFoundHttpException();
        }
        return $this->render('index', [
            'news' => $newsData
        ]);
    }
}