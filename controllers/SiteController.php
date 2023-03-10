<?php

namespace app\controllers;

use app\models\AccessionBook;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\CropInPegawai;
use app\models\EggData;
use app\models\Priode;
use app\models\Trials;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $priodeId = Priode::find()->orderBy("id desc")->one()->id;
        $model = new EggData(['priode_id' => $priodeId]);
        $summary = $model->getSummary('2021-08-01', '2021-08-31');

        if (Yii::$app->user->isGuest){
            $this->layout = "main-guest";
            return $this->render('index', [
                'summary' => $summary,
                'datasets' => $model->getChartDataset()
            ]);
        } else {
            return $this->render('index', [
                'summary' => $summary,
                'datasets' => $model->getChartDataset()
            ]);
        }
    }

    public function actionSummary()
    {
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $priodeId = Priode::find()->orderBy("id desc")->one()->id;
            $model = new EggData(['priode_id' => $priodeId]);
            if ($data = $model->getSummary('2021-08-01', '2021-08-31')) {
                return [
                    'status' => 200,
                    'data' => $data
                ];
            } else {
                return [
                    'status' => 404
                ];
            }
        }else{
            throw new ForbiddenHttpException("Request type not allowed!");
        }
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        $this->layout = 'main-login';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
