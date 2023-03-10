<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\EggData */
?>
<div class="egg-data-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'priode_id',
            'count',
            'source_type',
            'cage_id',
            'created_at',
            'created_by',
        ],
    ]) ?>

</div>
