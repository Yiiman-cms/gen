<?php
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

$asset = system\modules\gen\GiiAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="none">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <div class="container-fluid page-container">
        <?php $this->beginBody() ?>
        <?php
        NavBar::begin([
            'brandLabel' => Html::img($asset->baseUrl . '/logo.png',['style'=>'height:40px;margin-top:5px']),
            'brandUrl' => ['default/index'],
            'options' => ['class' => 'navbar-inverse navbar-fixed-top'],
        ]);
        echo Nav::widget([
            'options' => ['class' => 'nav navbar-nav navbar-right'],
            'items' => [
                ['label' => 'GEN', 'url' => ['default/index']],
                ['label' => 'مستندات', 'url' => 'http://www.yiiframework.com/doc-2.0/ext-gii-index.html'],
                ['label' => 'بازگشت به سایت', 'url' => Yii::$app->homeUrl],
            ],
        ]);
        NavBar::end();
        ?>
        <div class="container content-container">
            <?= $content ?>
        </div>
        <div class="footer-fix"></div>
    </div>
    <footer class="footer">
        <div class="container">
            <p class="pull-left">محصولی از  <a href="http://tokapps.ir">تیم طراحی وب توکاپس</a></p>
            
        </div>
    </footer>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
