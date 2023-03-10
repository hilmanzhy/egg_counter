<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ms_cage".
 *
 * @property int $id
 * @property string|null $code
 * @property string $name
 * @property string|null $created_at
 * @property int|null $created_by
 * @property int|null $is_active
 *
 * @property EggDatum[] $eggData
 * @property User $createdBy
 */
class MsCage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ms_cage';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at'], 'safe'],
            [['created_by', 'is_active'], 'integer'],
            [['code'], 'string', 'max' => 10],
            [['name'], 'string', 'max' => 100],
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
            'code' => Yii::t('app', 'Code'),
            'name' => Yii::t('app', 'Name'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'is_active' => Yii::t('app', 'Is Active'),
        ];
    }

    /**
     * Gets query for [[EggData]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEggData()
    {
        return $this->hasMany(EggDatum::className(), ['cage_id' => 'id']);
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
