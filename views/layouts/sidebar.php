<?php

use mdm\admin\components\MenuHelper;
use app\models\MsPegawai;
use yii\helpers\Url;

$pegawai = Yii::$app->user->getIdentity()->pegawai;
$callback = function ($menu) {
   return [
       'label' => $menu['name'],
       'url' => [$menu['route']],
       'icon' => $menu['icon'],
       'items' => $menu['children']
   ];
}
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= Url::home() ?>" class="brand-link bg-dark">
        <img src="<?=$assetDir?>/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light"><b>Monitor</b> Ayam</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div style="margin: auto 0px" class="image">
                <img src="<?=$assetDir?>/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?= $pegawai->name ?>
                </a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <?php
            echo \hail812\adminlte\widgets\Menu::widget([
                'items' => MenuHelper::getAssignedMenu(Yii::$app->user->id, null, $callback),
            ]);
            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
