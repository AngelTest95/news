<?php

namespace app\controllers;

use app\models\data\UserData;
use app\models\domain\exception\UnableToSaveException;
use app\models\domain\Register;
use app\models\domain\User;
use app\models\form\LoginForm;
use app\models\form\RegisterForm;
use app\models\repository\NewsRepository;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;

class IndexController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return true;
                        }
                    ],
                    [
                        'actions' => ['login', 'register'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return \Yii::$app->user->getIsGuest();
                        }
                    ],
                    [
                        'actions' => ['logout-user'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !\Yii::$app->user->getIsGuest();
                        }
                    ],
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        /* @var $newsRepo NewsRepository */
        $newsRepo = make(NewsRepository::class);
        /* @var $pagination Pagination */
        $pagination = make(Pagination::class);
        $pagination->totalCount = $newsRepo->getNewsCount();
        $pagination->pageSize = 10;
        $news = $newsRepo->getAllNews($pagination->offset, $pagination->limit);
        return $this->render('index', [
            'news' => $news,
            'pagination' => $pagination
        ]);
    }

    /**
     * Displays the login form and validates it. On successful validation logs the user.
     * @return array|string
     */
    public function actionLogin()
    {
        /* @var $model LoginForm*/
        $model = make(LoginForm::class);

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $errors = ActiveForm::validate($model);

            if ($errors) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $errors;
            }

            \Yii::$app->user->login(make(User::class, [$model->userData]), 0);
            return $this->redirect(Url::to('index'));
        }
        return $this->renderAjax('login-modal', [
            'model' => $model
        ]);
    }

    /**
     * Displays the registration form and validates it. On successful validation logs the user.
     * @return array|string
     */
    public function actionRegister()
    {
        /* @var $model RegisterForm*/
        $model = make(RegisterForm::class);

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $errors = ActiveForm::validate($model);

            if ($errors) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $errors;
            }

            $transaction = Yii::$app->db->beginTransaction();

            try {
                $userData = $model->save();
                /* @var $register Register*/
                $register = make(Register::class,[], ['email' => $userData->email]);
                \Yii::$app->session->setFlash('success', 'Successfully registered! Normally if this was a
             fully working site you would get an email to verify your account. Since it\'s not you can either
             go to the mail_queue table OR you can click here: <a href="' . $register->generateVerificationLink() . '">CLICK ME!</a> ');
                $transaction->commit();
            } catch (UnableToSaveException $e) {
                $transaction->rollBack();
                \Yii::$app->session->setFlash('error', $e->getMessage());
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->session->setFlash('error', 'Something went wrong');
            }

            return $this->redirect(Url::to(['index']));

        }
        return $this->renderAjax('register-modal', [
            'model' => $model
        ]);
    }

    /**
     * @return Response
     */
    public function actionLogoutUser()
    {
        \Yii::$app->user->logout();
        return $this->redirect(Url::to('index'));
    }
}