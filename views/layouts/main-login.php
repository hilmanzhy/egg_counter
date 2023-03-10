<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Url;

\hail812\adminlte3\assets\AdminLteAsset::register($this);
$this->registerCssFile('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700');
$this->registerCssFile('https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css');
\hail812\adminlte3\assets\PluginAsset::register($this)->add(['fontawesome', 'icheck-bootstrap']);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html class="test">
<head>
<style type="text/css">
    body {
        background-image: url(<?= Url::to("@web" . "/images/login-background.jpg", true) ?>) !important;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
    
    div.login-logo {
        padding: 5px 0px !important;
        margin: 0px;
    }
    
    .login-logo a, .register-logo a {
        color: white !important;
    }

</style>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login | <?= Yii::$app->name ?></title>
    <meta name="description" content="Solusi bisnis bagi petelu ayam.">
    <meta name="author" content="KebunPintar.com">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <?php $this->head() ?>
</head>
<body class="hold-transition login-page">
<?php  $this->beginBody() ?>
<div class="login-box elevation-4">
    <div class="login-logo">
        <a href="<?=Yii::$app->homeUrl?>"><b>Monitor</b> Ayam</a>
    </div>
    <!-- /.login-logo -->

    <?= $content ?>
</div>
<!-- /.login-box -->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
