<?php
namespace app\api\modules\v1\controllers;

use yii\filters\auth\HttpBasicAuth;
use yii\rest\ActiveController;

class EggDataController extends ActiveController
{
    public $modelClass = 'app\models\EggData';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::class,
        ];
        return $behaviors;
    }
}
