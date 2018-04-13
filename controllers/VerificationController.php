<?php

namespace app\controllers;

use app\models\domain\exception\UnableToSaveException;
use app\models\domain\User;
use app\models\form\VerificationForm;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;

class VerificationController extends Controller
{
    public function actionIndex($token)
    {
        /* @var $model VerificationForm */
        $model = make(VerificationForm::class);
        $model->token = $token;
        if ($model->validate('token')) {
            $isValid = 1;
            $message = 'Please enter a username and password for your account';
        } else {
            $isValid = 0;
            $message = 'Error. Wrong token';
        }
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $errors = ActiveForm::validate($model);
            if ($errors) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $errors;
            } else {
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    $userData = $model->save();
                    $transaction->commit();
                    \Yii::$app->user->login(make(User::class, [$userData]), 0);
                    \Yii::$app->session->setFlash('success', 'Successfully finished registration');
                } catch (UnableToSaveException $e) {
                    $transaction->rollBack();
                    \Yii::$app->session->setFlash('error', $e->getMessage());
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    \Yii::$app->session->setFlash('error', 'Oops. Something went wrong.');
                }

                return $this->redirect(Url::to(['/index']));
            }
        }
        return $this->render('index', [
            'message' => $message,
            'isValid' => $isValid,
            'model' => $model,
        ]);
    }
}