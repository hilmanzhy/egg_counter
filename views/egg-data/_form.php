<?php

use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EggData */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="egg-data-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'source_type')->dropdownList(['MANUAL' => 'MANUAL', 'SENSOR' => 'SENSOR']) ?>

    <?= $form->field($model, 'count_ok')->textInput() ?>

    <?= $form->field($model, 'count_crack')->textInput() ?>

	<?php if (Yii::$app->request->isAjax){ ?>
        <?php ActiveForm::end(); ?>
	<?php } ?>

</div>
