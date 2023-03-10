<?php

use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\EggData */

$menuTitle = Yii::t('app', 'Tambah Data Telur');
$this->title = Yii::t('app', $menuTitle . ' | ' . Yii::$app->name);
$this->params['menuTitle'] = $menuTitle;
$this->params['breadcrumbs'][] = $menuTitle;
?>

<div class="container-fluid">
<div class="card border-primary">
    <div class="card-header bg-primary">
        <h5 class="m-0"><span class="fas fa-plus"></span> Form Tambah Data</h5>
    </div>
    <div class="card-body">
        <div class="egg-data-create">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
    <div class="card-footer text-right">
	        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
</div>
        <?php ActiveForm::end(); ?>
</div>
