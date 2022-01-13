<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator YiiMan\gen\generators\crud\Generator */
global $mName;
echo "<?php\n";
?>

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = <?= $generator->generateString('ثبت ' . Inflector::camel2words(StringHelper::basename($generator->CrudName))) ?>;
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString((Inflector::camel2words(StringHelper::basename($mName)))) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-create">

    <?= "<?= " ?>$this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
