<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Priode */
?>
<div class="priode-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'chicken_count',
            'chicken_ages',
            'created_by',
            'created_at',
            'last_productivity',
        ],
    ]) ?>

</div>
