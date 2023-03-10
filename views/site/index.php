<?php

use dosamigos\chartjs\ChartJs;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$menuTitle = Yii::t('app', 'Dashboard');
$this->title = Yii::t('app', $menuTitle . ' | ' . Yii::$app->name);
$this->params['menuTitle'] = $menuTitle;
$this->params['breadcrumbs'][] = $menuTitle;

$eggPercent = ($summary['count_egg_today'] / $summary['chicken_count']) * 100;
$barWidth = $eggPercent > 100 ? "100%" : $eggPercent . "%";
?>
<div class="container-fluid">
    <div class="card border-primary">
        <div class="card-header bg-primary">
            <h5 class="m-0"><span class="fas fa-tachometer-alt"></span> Data Summary</h5>
        </div>
        <div class="card-body pb-1">
            <div class="row">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-6">
                            <?= \hail812\adminlte\widgets\InfoBox::widget([
                                'id' => 'info-count-sensor',
                                'text' => 'Total Telur (Sensor)',
                                'number' => $summary['count_sensor'],
                                'icon' => 'fas fa-magic',
                                'theme' => 'info',
                                /* 'linkUrl' => Url::to(['']) */
                            ]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= \hail812\adminlte\widgets\InfoBox::widget([
                                'id' => 'info-count-manual',
                                'text' => 'Total Telur (Manual)',
                                'number' => $summary['count_manual'],
                                'icon' => 'fas fa-pen-nib',
                                'theme' => 'info'
                                /* 'linkUrl' => Url::to(['']) */
                            ]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?= \hail812\adminlte\widgets\InfoBox::widget([
                                'id' => 'info-count-crack',
                                'text' => 'Total Telur Pecah',
                                'number' => $summary['count_crack'],
                                'icon' => 'fas fa-compress',
                                'theme' => 'danger',
                                /* 'linkUrl' => Url::to(['']) */
                            ]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= \hail812\adminlte\widgets\InfoBox::widget([
                                'id' => 'info-count-diff',
                                'text' => 'Selisih Telur',
                                'number' => abs($summary['count_sensor'] - $summary['count_manual']),
                                'icon' => 'fas fa-calculator',
                                'theme' => 'warning'
                                /* 'linkUrl' => Url::to(['']) */
                            ]) ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div style="min-height: 92%;" class="info-box bg-success">
                        <span id="w1-icon" class="info-box-icon bg-"><i class="fas fa-percentage"></i></span>
                        <div id="w4-content" class="info-box-content">
                            <span class="info-box-text">Prosentase Produksi Telur</span>
                            <span id="info-count-percentage" class="info-box-number"><?= $eggPercent ?>%</span>
                            <div class="progress">
                                <div id="info-count-percentage-bar" class="progress-bar" style="width: <?= $barWidth ?> ;"></div>
                            </div>
                            <span class="progress-description"><span id="info-count-egg-today"><?= $summary['count_egg_today'] ?></span> telur dari <span id="info-chicken-count"> <?= $summary['chicken_count'] ?></span> total ayam</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="row">
        <div class="col-xl-6">
            <div class="card border-primary">
                <div class="card-header bg-default">
                    <h5 class="m-0"><span class="fas fa-chart-line"></span> Produksi Telur/Jam</h5>
                </div>
                <div class="card-body">
                    <?= ChartJs::widget([
                        'type' => 'line',
                        'id' => 'eggHourChart',
                        'options' => [
                            'height' => 150,
                        ],
                        'data' => [
                            'labels' => array_keys($datasets['egg_hour_dataset']),
                            'datasets' => [
                                [
                                    'label' => "Data Sensor",
                                    'backgroundColor' => "rgba(255,99,132,0.2)",
                                    'borderColor' => "rgba(255,99,132,1)",
                                    'pointBackgroundColor' => "rgba(255,99,132,1)",
                                    'pointBorderColor' => "#fff",
                                    'pointHoverBackgroundColor' => "#fff",
                                    'pointHoverBorderColor' => "rgba(255,99,132,1)",
                                    'data' => array_values($datasets['egg_hour_dataset'])
                                ],
                            ]
                        ],
                        'plugins' =>
                        new \yii\web\JsExpression('
                        [{
                            afterInit(chart, args, options) {
                                jsVar.eggHourChart = chart;
                            }
                        }]')
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card border-primary">
                <div class="card-header bg-default">
                    <h5 class="m-0"><span class="fas fa-chart-line"></span> Produksi Telur Harian</h5>
                </div>
                <div class="card-body">
                    <?= ChartJs::widget([
                        'type' => 'line',
                        'options' => [
                            'height' => 150,
                        ],
                        'data' => [
                            'labels' => ArrayHelper::getColumn($datasets['egg_day_dataset'], 'time_series'),
                            'datasets' => [
                                [
                                    'label' => "Data Sensor",
                                    'backgroundColor' => "rgba(100,99,132,0.2)",
                                    'borderColor' => "rgba(100,99,132,1)",
                                    'pointBackgroundColor' => "rgba(100,99,132,1)",
                                    'pointBorderColor' => "#fff",
                                    'pointHoverBackgroundColor' => "#fff",
                                    'pointHoverBorderColor' => "rgba(100,99,132,1)",
                                    'data' => ArrayHelper::getColumn($datasets['egg_day_dataset'], 'sensor_count_ok')
                                ],
                                [
                                    'label' => "Data Manual",
                                    'backgroundColor' => "rgba(132,0,132,0.2)",
                                    'borderColor' => "rgba(132,0,132,1)",
                                    'pointBackgroundColor' => "rgba(132,0,132,1)",
                                    'pointBorderColor' => "#fff",
                                    'pointHoverBackgroundColor' => "#fff",
                                    'pointHoverBorderColor' => "rgba(132,0,132,1)",
                                    'data' => ArrayHelper::getColumn($datasets['egg_day_dataset'], 'manual_count_ok')
                                ],
                                [
                                    'label' => "Data Manual (Pecah)",
                                    'backgroundColor' => "rgba(255,99,132,0.2)",
                                    'borderColor' => "rgba(255,99,132,1)",
                                    'pointBackgroundColor' => "rgba(255,99,132,1)",
                                    'pointBorderColor' => "#fff",
                                    'pointHoverBackgroundColor' => "#fff",
                                    'pointHoverBorderColor' => "rgba(255,99,132,1)",
                                    'data' => ArrayHelper::getColumn($datasets['egg_day_dataset'], 'manual_count_crack')
                                ],
                            ]
                        ],
                        'plugins' =>
                        new \yii\web\JsExpression('
                        [{
                            afterInit(chart, args, options) {
                                jsVar.eggDayChart = chart;
                            }
                        }]')
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="card border-primary">
        <div class="card-header bg-default">
            <h5 class="m-0"><span class="fas fa-chart-line"></span> Produksi Telur Triwulan</h5>
        </div>
        <div class="card-body">
            <?= ChartJs::widget([
                'type' => 'line',
                'options' => [
                    'height' => 100,
                ],
                'data' => [
                    'labels' => ArrayHelper::getColumn($datasets['egg_month_dataset'], 'time_series'),
                    'datasets' => [
                        [
                            'label' => "Data Sensor",
                            'backgroundColor' => "rgba(100,99,132,0.2)",
                            'borderColor' => "rgba(100,99,132,1)",
                            'pointBackgroundColor' => "rgba(100,99,132,1)",
                            'pointBorderColor' => "#fff",
                            'pointHoverBackgroundColor' => "#fff",
                            'pointHoverBorderColor' => "rgba(100,99,132,1)",
                            'data' => ArrayHelper::getColumn($datasets['egg_month_dataset'], 'sensor_count_ok')
                        ],
                        [
                            'label' => "Data Manual",
                            'backgroundColor' => "rgba(132,0,132,0.2)",
                            'borderColor' => "rgba(132,0,132,1)",
                            'pointBackgroundColor' => "rgba(132,0,132,1)",
                            'pointBorderColor' => "#fff",
                            'pointHoverBackgroundColor' => "#fff",
                            'pointHoverBorderColor' => "rgba(132,0,132,1)",
                            'data' => ArrayHelper::getColumn($datasets['egg_month_dataset'], 'manual_count_ok')
                        ],
                        [
                            'label' => "Data Manual (Pecah)",
                            'backgroundColor' => "rgba(255,99,132,0.2)",
                            'borderColor' => "rgba(255,99,132,1)",
                            'pointBackgroundColor' => "rgba(255,99,132,1)",
                            'pointBorderColor' => "#fff",
                            'pointHoverBackgroundColor' => "#fff",
                            'pointHoverBorderColor' => "rgba(255,99,132,1)",
                            'data' => ArrayHelper::getColumn($datasets['egg_month_dataset'], 'manual_count_crack')
                        ],
                    ]
                ],
                'plugins' =>
                new \yii\web\JsExpression('
                [{
                    afterInit(chart, args, options) {
                        jsVar.eggMonthChart = chart;
                    }
                }]')
            ]) ?>
        </div>
    </div>
    <div class="card border-primary">
        <div class="card-header bg-default">
            <h5 class="m-0"><span class="fas fa-chart-line"></span> Produksi Telur Mingguan</h5>
        </div>
        <div class="card-body">
            <?= ChartJs::widget([
                'type' => 'line',
                'options' => [
                    'height' => 100,
                ],
                'data' => [
                    'labels' => ArrayHelper::getColumn($datasets['egg_week_dataset'], 'time_series'),
                    'datasets' => [
                        [
                            'label' => "Data Sensor",
                            'backgroundColor' => "rgba(100,99,132,0.2)",
                            'borderColor' => "rgba(100,99,132,1)",
                            'pointBackgroundColor' => "rgba(100,99,132,1)",
                            'pointBorderColor' => "#fff",
                            'pointHoverBackgroundColor' => "#fff",
                            'pointHoverBorderColor' => "rgba(100,99,132,1)",
                            'data' => ArrayHelper::getColumn($datasets['egg_week_dataset'], 'sensor_count_ok')
                        ],
                        [
                            'label' => "Data Manual",
                            'backgroundColor' => "rgba(132,0,132,0.2)",
                            'borderColor' => "rgba(132,0,132,1)",
                            'pointBackgroundColor' => "rgba(132,0,132,1)",
                            'pointBorderColor' => "#fff",
                            'pointHoverBackgroundColor' => "#fff",
                            'pointHoverBorderColor' => "rgba(132,0,132,1)",
                            'data' => ArrayHelper::getColumn($datasets['egg_week_dataset'], 'manual_count_ok')
                        ],
                        [
                            'label' => "Data Manual (Pecah)",
                            'backgroundColor' => "rgba(255,99,132,0.2)",
                            'borderColor' => "rgba(255,99,132,1)",
                            'pointBackgroundColor' => "rgba(255,99,132,1)",
                            'pointBorderColor' => "#fff",
                            'pointHoverBackgroundColor' => "#fff",
                            'pointHoverBorderColor' => "rgba(255,99,132,1)",
                            'data' => ArrayHelper::getColumn($datasets['egg_week_dataset'], 'manual_count_crack')
                        ],
                    ]
                ],
                'plugins' =>
                new \yii\web\JsExpression('
                [{
                    afterInit(chart, args, options) {
                        jsVar.eggWeekChart = chart;
                    }
                }]')
            ]) ?>
        </div>
    </div>
</div>

<?php
$jsVar = [
    'urlGetSummary' => Url::to(['summary']),
    'ajaxRefreshInterval' => Yii::$app->params['ajaxRefreshInterval']
];

$js = <<< EJS
$(window).resize(function() {
  if ($(this).width() >= 1280) {
    //do something
    console.log("LARGE");
  }
  else if ($(this).width() < 1280 && $(this).width()>= 980) {
    //do something
    console.log("SMALL");
  }
});
setInterval(function() {
    $.ajax({
        url: jsVar.urlGetSummary,
        success: function(data) {
            if (data.status == 200) {
                data = data.data;
                eggPercentVal = (data.count_egg_today / data.chicken_count) * 100;
                barWidthVal = eggPercentVal > 100 ? "100%" : eggPercentVal + "%";

                $('#info-count-sensor').find('span.info-box-number').html(data.count_sensor);
                $('#info-count-manual').find('span.info-box-number').html(data.count_manual);
                $('#info-count-crack').find('span.info-box-number').html(data.count_crack);
                $('#info-count-diff').find('span.info-box-number').html(Math.abs(data.count_manual - data.count_sensor));
                $('#info-count-egg-today').html(data.count_egg_today);
                $('#info-chicken-count').html(data.chicken_count);
                $('#info-count-percentage').html(eggPercentVal + "%");
                $('#info-count-percentage-bar').attr('style', "width: " + barWidthVal);

                updateAllChart(data);
            }
        }
    });
}, jsVar.ajaxRefreshInterval);

function updateAllChart(data) {
    addData(jsVar.eggHourChart, data.egg_hour_data.label, data.egg_hour_data.data);
    addData(jsVar.eggDayChart, data.egg_day_data.label, data.egg_day_data.data);
    addData(jsVar.eggMonthChart, data.egg_day_data.label, data.egg_day_data.data);
    addData(jsVar.eggWeekChart, data.egg_week_data.label, data.egg_week_data.data);
}

function addData(chart, label, data) {
    const len = chart.data.labels.length;

    if (chart.data.labels[len-1] != label) {
        chart.data.labels.push(label);
        chart.data.datasets.forEach(function(dataset, index) {
            dataset.data.push(data[index]);
        });
        chart.update();
    } else {
        chart.data.datasets.forEach(function(dataset, index) {
            const dLen = dataset.data.length;
            dataset.data[dLen - 1] = data[index];
        });
        chart.update();
    }
}
EJS;

$this->registerJs($js);
$this->registerJsVar('jsVar', $jsVar);
?>
