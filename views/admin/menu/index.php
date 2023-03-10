<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel mdm\admin\models\searchs\Menu */

$menuTitle = Yii::t('app', 'Menu');
$this->title = Yii::t('app', $menuTitle . ' | ' . Yii::$app->name);
$this->params['menuTitle'] = $menuTitle;
$this->params['breadcrumbs'][] = $menuTitle;
?>
<div class="card border-primary">
    <div class="card-header bg-primary"><span class="fas fa-list"></span> Daftar Menu</div>
    <div class="card-body">
        <div class="menu-index">
            <?php // echo $this->render('_search', ['model' => $searchModel]);  
            ?>

            <p>
                <?= Html::a(Yii::t('rbac-admin', 'Create Menu'), ['create'], ['class' => 'btn btn-success']) ?>
            </p>

            <?php Pjax::begin(); ?>
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'name',
                    [
                        'attribute' => 'menuParent.name',
                        'filter' => Html::activeTextInput($searchModel, 'parent_name', [
                            'class' => 'form-control', 'id' => null
                        ]),
                        'label' => Yii::t('rbac-admin', 'Parent'),
                    ],
                    'route',
                    'order',
                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]);
            ?>
            <?php Pjax::end(); ?>

        </div>
    </div>
</div>
