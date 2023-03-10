<?php

namespace app\components;

use Yii;
use yii\web\User as WebUser;

class User extends WebUser
{
    protected function afterLogin($identity, $cookieBased, $duration)
    {
        parent::afterLogin($identity, $cookieBased, $duration);
        $pegawai = $identity->pegawai;
        $session = Yii::$app->session;
    }
}
