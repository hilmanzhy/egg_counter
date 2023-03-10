<?php
namespace app\models;

use mdm\admin\components\UserStatus;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use app\models\User;
use kartik\password\StrengthValidator;

/**
 * Signup form
 */
class Signup extends Model
{
    public $username;
    public $userId;
    public $pegawai_id;
    public $email;
    public $password;
    public $retypePassword;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $class = 'app\models\User';
        return [
            [['pegawai_id', 'userId'], 'integer'],
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => $class, 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => $class, 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['retypePassword', 'required'],
            ['retypePassword', 'compare', 'compareAttribute' => 'password'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->status = ArrayHelper::getValue(Yii::$app->params, 'user.defaultStatus', UserStatus::ACTIVE);
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->pegawai_id = $this->pegawai_id;
            if ($user->save()) {
                return $user;
            }
        }

        return null;
    }

}
