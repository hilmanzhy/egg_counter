<?php
namespace app\api\modules\v1\controllers;

use yii\filters\auth\HttpBasicAuth;
use yii\rest\ActiveController;

class UserController extends ActiveController
{
    public $modelClass = 'app\models\User';

    public function actionLogin (){
        return "hello";
    }
}
