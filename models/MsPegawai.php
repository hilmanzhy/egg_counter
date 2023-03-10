<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ms_pegawai".
 *
 * @property int|null $id
 * @property string|null $nip
 * @property string|null $nik
 * @property string|null $name
 */
class MsPegawai extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ms_pegawai';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['nip', 'nik', 'name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'nip' => Yii::t('app', 'Nip'),
            'nik' => Yii::t('app', 'Nik'),
            'name' => Yii::t('app', 'Name'),
        ];
    }
}
