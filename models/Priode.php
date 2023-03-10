<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "priode".
 *
 * @property int $id
 * @property string|null $title
 * @property int $chicken_count
 * @property int|null $chicken_ages
 * @property int|null $created_by
 * @property string|null $created_at
 * @property string|null $last_productivity
 *
 * @property EggDatum[] $eggData
 * @property User $createdBy
 */
class Priode extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'priode';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['chicken_count'], 'required'],
            [['chicken_count', 'chicken_ages', 'created_by'], 'integer'],
            [['created_at', 'last_productivity'], 'safe'],
            [['title'], 'string', 'max' => 100],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'chicken_count' => Yii::t('app', 'Jumlah Ayam'),
            'chicken_ages' => Yii::t('app', 'Umur AYam'),
            'created_by' => Yii::t('app', 'Dibuat Oleh'),
            'created_at' => Yii::t('app', 'Dibuat Pada'),
        ];
    }

    /**
     * Gets query for [[EggData]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEggData()
    {
        return $this->hasMany(EggDatum::className(), ['priode_id' => 'id']);
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }
}
