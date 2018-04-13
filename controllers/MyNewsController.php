<?php

namespace app\controllers;

use app\models\domain\DeleteNews;
use app\models\domain\exception\UnableToSaveException;
use app\models\form\AddNewsForm;
use app\models\repository\NewsRepository;
use yii\filters\AccessControl;
use yii\web\Controller;
use Yii;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

class MyNewsController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'add'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !\Yii::$app->user->getIsGuest();
                        }
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'matchCallback' => function () {
                            $newsId = \Yii::$app->request->get('id');
                            if ($newsData = make(NewsRepository::class)->getById($newsId)) {
                                return $newsData->user_id == Yii::$app->user->getId();
                            }
                            return false;
                        }
                    ],
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        return $this->render('index', [
            'news' => make(NewsRepository::class)->getAllNewsByUserId(\Yii::$app->user->getId())
        ]);
    }

    public function actionAdd()
    {
        /* @var $model AddNewsForm */
        $model = make(AddNewsForm::class);
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $model->userId = Yii::$app->user->getId();
            $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
            $model->upload();
            $errors = ActiveForm::validate($model);
            if ($errors) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $errors;
            } else {
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    $model->save();
                    $transaction->commit();
                    \Yii::$app->session->setFlash('success', 'News article saved successfully');
                } catch (UnableToSaveException $e) {
                    $transaction->rollBack();
                    \Yii::$app->session->setFlash('error', $e->getMessage());
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    \Yii::$app->session->setFlash('error', 'Ooops. Something went wrong');
                }

                return $this->redirect(['index']);
            }
        }
        return $this->render('add-news-modal', [
            'model' => $model
        ]);
    }

    /**
     * Deletes an article
     * @param integer $id
     * @return Response
     */
    public function actionDelete($id)
    {
        make(DeleteNews::class, [], ['newsId' => $id])->delete();
        \Yii::$app->session->setFlash('success', 'News article deleted successfully');
        return $this->redirect(['/my-news/index']);
    }
}