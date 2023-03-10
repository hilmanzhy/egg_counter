<?php

use yii\helpers\Html;

?>
<!-- Navbar -->
<nav style="margin-left: 0px !important;" class="main-header navbar navbar-expand navbar-dark bg-dark">
    <ul class="navbar-nav">
        <li class="nav-item">
            <h5 class="m-0">Monitor Ayam</h5>
        </li>
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <?= Html::a('<i class="fas fa-sign-in-alt"></i>', ['/site/login'], ['data-method' => 'post', 'class' => 'nav-link']) ?>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->
