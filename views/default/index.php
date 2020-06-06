<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $generators \system\modules\gen\Generator[] */
/* @var $content string */

$generators = Yii::$app->controller->module->generators;
$this->title = 'کد انجین توکاپس';
?>
<div class="default-index">
    <div class="page-header">
        <h1 class="text-center">به موتور کد توکاپس خوش آمدید</h1>
    </div>

    <p class="lead text-right" style="direction:rtl">با خودساز یکی از کدهای ذیل شروع کنید:</p>

    <div class="row">
        <?php foreach ($generators as $id => $generator): ?>
        <div class="generator col-lg-4">
            <h3><?= Html::encode($generator->getName()) ?></h3>
            <p><?= $generator->getDescription() ?></p>
            <p><?= Html::a('Start &raquo;', ['default/view', 'id' => $id], ['class' => 'btn btn-default']) ?></p>
        </div>
        <?php endforeach; ?>
    </div>

  

</div>
