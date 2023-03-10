<?php

namespace app\models;

use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "egg_data".
 *
 * @property int $id
 * @property int $priode_id
 * @property int $count
 * @property int|null $source_type
 * @property int $cage_id
 * @property string|null $created_at
 * @property int|null $created_by
 *
 * @property Priode $priode
 * @property MsCage $cage
 */
class EggData extends \yii\db\ActiveRecord
{
    const SOURCE_TYPE_SENSOR = 'SENSOR';
    const SOURCE_TYPE_MANUAL = 'MANUAL';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'egg_data';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['count_ok', 'source_type'], 'required'],
            [['priode_id', 'count_ok', 'count_crack', 'cage_id', 'created_by'], 'integer'],
            [['priode_id'], 'default', 'value' => Priode::find()->orderBy("id desc")->one()->id],
            [['created_at'], 'default', 'value' => new Expression('now()')], 
            ['source_type', 'string', 'max' => 10],
            [['created_at'], 'safe'],
            [['id'], 'unique'],
            [['priode_id'], 'exist', 'skipOnError' => true, 'targetClass' => Priode::className(), 'targetAttribute' => ['priode_id' => 'id']],
            [['cage_id'], 'exist', 'skipOnError' => true, 'targetClass' => MsCage::className(), 'targetAttribute' => ['cage_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'priode_id' => Yii::t('app', 'Priode ID'),
            'count_ok' => Yii::t('app', 'Jumlah Telur Normal'),
            'count_crack' => Yii::t('app', 'Jumlah Telur Pecah'),
            'source_type' => Yii::t('app', 'Sumber Data'),
            'cage_id' => Yii::t('app', 'Cage ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    public function getChartDataset () {
        $today = date('Y-m-d', time());
        $startWeek = Priode::find()->select(new Expression("week(created_at, 1) as start_week"))->where(['id' => $this->priode_id])->asArray()->one()['start_week'];
        $eggHourDataset = static::find()
            ->select(new Expression("date_format(created_at,'%H:%i:%s') as time_series"))
            ->addSelect("count_ok")
            ->where("date(created_at) = '$today'")
            ->andWhere(["source_type" => static::SOURCE_TYPE_SENSOR, 'priode_id' => $this->priode_id])
            ->asArray()
            ->all();

        $eggMonthDataset = Yii::$app->db
                ->createCommand("select ts.time_series, COALESCE(ds.sensor_count_ok, 0) sensor_count_ok, 
                    COALESCE(dm.manual_count_ok, 0) manual_count_ok, COALESCE(dm.manual_count_crack, 0) manual_count_crack
                    from (
                        select date(created_at) time_series
                        from egg_data ed 
                        where created_at > (now() - INTERVAL 3 month) and priode_id = $this->priode_id
                        group by date(created_at)
                    ) ts
                    left join (
                        select date(created_at) time_series, sum(count_ok) sensor_count_ok
                        from egg_data ed 
                        where created_at > (now() - INTERVAL 3 month) and source_type = '" . static::SOURCE_TYPE_SENSOR . "' and priode_id = $this->priode_id
                        group by date(created_at)
                    ) ds on ds.time_series = ts.time_series
                    left join (
                        select date(created_at) time_series, sum(count_ok) manual_count_ok, sum(count_crack) manual_count_crack
                        from egg_data ed 
                        where created_at > (now() - INTERVAL 3 month) and source_type = '" . static::SOURCE_TYPE_MANUAL . "' and priode_id = $this->priode_id
                        group by date(created_at)
                    ) dm on dm.time_series = ts.time_series")
                ->queryAll();

        $eggWeeksDataset = Yii::$app->db
            ->createCommand("select concat('Minggu ke-', ts.time_series - $startWeek + 1) time_series, COALESCE(ds.sensor_count_ok, 0) sensor_count_ok, 
                COALESCE(dm.manual_count_ok, 0) manual_count_ok, COALESCE(dm.manual_count_crack, 0) manual_count_crack
                from (
                    select week(created_at, 1) as time_series
                    from egg_data ed 
                    where priode_id = $this->priode_id
                    group by week(created_at, 1)
                ) ts 
                left join (
                    select week(created_at, 1) as time_series, sum(count_ok) sensor_count_ok
                    from egg_data ed 
                    where priode_id = $this->priode_id and source_type = '" . static::SOURCE_TYPE_SENSOR . "'
                    group by week(created_at, 1)
                ) ds on ds.time_series = ts.time_series
                left join (
                    select week(created_at, 1) as time_series, sum(count_ok) manual_count_ok, sum(count_crack) manual_count_crack
                    from egg_data ed 
                    where priode_id = $this->priode_id and source_type = '" . static::SOURCE_TYPE_MANUAL . "'
                    group by week(created_at, 1)
                ) dm on dm.time_series = ts.time_series")
            ->queryAll();

        $eggHourDataset = ArrayHelper::map($eggHourDataset, 'time_series', 'count_ok');

        $eggDayDataset = [];
        $len = count($eggMonthDataset);
        $start = $len - 7;
        $start = $start > 0 ? $start : 0;

        for ($i = $start; $i < $len; $i++) {
            array_push($eggDayDataset, $eggMonthDataset[$i]);
        }

        return [
            'egg_hour_dataset' => $eggHourDataset,
            'egg_day_dataset' => $eggDayDataset,
            'egg_month_dataset' => $eggMonthDataset,
            'egg_week_dataset' => $eggWeeksDataset
        ];
    }

    public function getSummary($dateStart, $dateEnd) {
        $today = date('Y-m-d', time());
        $startWeek = Priode::find()->select(new Expression("week(created_at, 1) as start_week"))->where(['id' => $this->priode_id])->asArray()->one()['start_week'];
        $summary = static::find()->where("date(created_at) = '$today'")
            ->andFilterWhere(["priode_id" => $this->priode_id])
            ->select(['source_type', 'sum(count_ok) as count_ok', 'sum(count_crack) as count_crack'])
            ->groupBy("source_type")
            ->all();


        $summary = ArrayHelper::index($summary, null, 'source_type');
        $countEggToday = static::find()->where("date(created_at) = '$today'")
               ->andWhere(['source_type' => static::SOURCE_TYPE_SENSOR])
               ->select("sum(count_ok) count_ok")
               ->one()->count_ok;

        $eggHourData = static::find()
            ->select(new Expression("date_format(created_at,'%H:%i:%s') as time_series"))
            ->addSelect("count_ok")
            ->where("date(created_at) = '$today'")
            ->andWhere(["source_type" => static::SOURCE_TYPE_SENSOR, 'priode_id' => $this->priode_id])
            ->orderBy("id desc")
            ->asArray()
            ->one();

        $eggDayData = Yii::$app->db
                ->createCommand("select ts.time_series, COALESCE(ds.sensor_count_ok, 0) sensor_count_ok, 
                    COALESCE(dm.manual_count_ok, 0) manual_count_ok, COALESCE(dm.manual_count_crack, 0) manual_count_crack
                    from (
                        select date(created_at) time_series
                        from egg_data ed 
                        where date(created_at) >= '$today' and priode_id = $this->priode_id
                        group by date(created_at)
                        order by id desc
                    ) ts
                    left join (
                        select date(created_at) time_series, sum(count_ok) sensor_count_ok
                        from egg_data ed 
                        where date(created_at) >= '$today' and source_type = 'SENSOR' and priode_id = $this->priode_id
                        group by date(created_at)
                    ) ds on ds.time_series = ts.time_series
                    left join (
                        select date(created_at) time_series, sum(count_ok) manual_count_ok, sum(count_crack) manual_count_crack
                        from egg_data ed 
                        where date(created_at) >= '$today' and source_type = 'MANUAL' and priode_id = $this->priode_id
                        group by date(created_at)
                    ) dm on dm.time_series = ts.time_series")
                ->queryOne();

        $eggWeekData = Yii::$app->db
            ->createCommand("select concat('Minggu ke-', ts.time_series - $startWeek + 1) time_series, COALESCE(ds.sensor_count_ok, 0) sensor_count_ok, 
                COALESCE(dm.manual_count_ok, 0) manual_count_ok, COALESCE(dm.manual_count_crack, 0) manual_count_crack
                from (
                    select week(created_at, 1) as time_series
                    from egg_data ed 
                    where priode_id = $this->priode_id and week(created_at, 1) = week('$today', 1)
                    group by week(created_at, 1)
                ) ts 
                left join (
                    select week(created_at, 1) as time_series, sum(count_ok) sensor_count_ok
                    from egg_data ed 
                    where priode_id = $this->priode_id and source_type = '" . static::SOURCE_TYPE_SENSOR . "' and week(created_at, 1) = week('$today', 1)
                    group by week(created_at, 1)
                ) ds on ds.time_series = ts.time_series
                left join (
                    select week(created_at, 1) as time_series, sum(count_ok) manual_count_ok, sum(count_crack) manual_count_crack
                    from egg_data ed 
                    where priode_id = $this->priode_id and source_type = '" . static::SOURCE_TYPE_MANUAL . "' and week(created_at, 1) = week('$today', 1)
                    group by week(created_at, 1)
                ) dm on dm.time_series = ts.time_series")
            ->queryOne();

        $countSensor = $summary[static::SOURCE_TYPE_SENSOR][0]['count_ok'];
        $countManual = $summary[static::SOURCE_TYPE_MANUAL][0]['count_ok'];
        $countCrack = $summary[static::SOURCE_TYPE_MANUAL][0]['count_crack'];

        $summary = [
            'count_sensor' => $countSensor == null ? 0 : $countSensor,
            'count_manual' => $countManual ==  null ? 0 : $countManual,
            'count_crack' => $countCrack == null ? 0 : $countCrack,
            'count_egg_today' => is_null($countEggToday) ? 0 : $countEggToday,
            'chicken_count' => Priode::findOne($this->priode_id)->chicken_count,
            'egg_hour_data' => [
                'label' => $eggHourData['time_series'],
                'data' => [
                    $eggHourData['count_ok']
                ]
            ],
            'egg_day_data' => [
                'label' => $eggDayData['time_series'],
                'data' => [
                    $eggDayData['sensor_count_ok'],
                    $eggDayData['manual_count_ok'],
                    $eggDayData['manual_count_crack']
                ]
            ],
            'egg_week_data' => [
                'label' => $eggWeekData['time_series'],
                'data' => [
                    $eggWeekData['sensor_count_ok'],
                    $eggWeekData['manual_count_ok'],
                    $eggWeekData['manual_count_crack']
                ]
            ]
        ];

        return $summary;
    }

    /**
     * Gets query for [[Priode]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPriode()
    {
        return $this->hasOne(Priode::className(), ['id' => 'priode_id']);
    }

    /**
     * Gets query for [[Cage]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCage()
    {
        return $this->hasOne(MsCage::className(), ['id' => 'cage_id']);
    }
}
