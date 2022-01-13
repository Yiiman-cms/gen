<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator YiiMan\gen\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
global $mName;
echo "<?php\n";
?>

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = <?= strtr($generator->generateString('ویرایش ' .
    Inflector::camel2words(StringHelper::basename($generator->CrudName)) .
    ': {nameAttribute}', ['nameAttribute' => '{nameAttribute}']), [
    '{nameAttribute}\'' => '\' . $model->' . $generator->getNameAttribute()
]) ?>;


\system\widgets\topMenu\TopMenuWidget::addBtb(
'add',
<?= $generator->generateString('ثبت ' . Inflector::camel2words(StringHelper::basename($generator->CrudName))) ?>,
'success' ,
null ,
Yii::$app->Options->BackendUrl . '/<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>/default/create'
);

\system\widgets\topMenu\TopMenuWidget::addBtb(
'assignment',
<?= $generator->generateString('بازبینی ' . Inflector::camel2words(StringHelper::basename($generator->CrudName))) ?>,
'info' ,
null ,
Yii::$app->Options->BackendUrl . '/<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>/default/view?id='.$model->id
);



$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString((Inflector::camel2words(StringHelper::basename($mName)))) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model-><?= $generator->getNameAttribute() ?>, 'url' => ['view', <?= $urlParams ?>]];
$this->params['breadcrumbs'][] = <?= $generator->generateString('ویرایش') ?>;
\system\widgets\backLang\backLangWidget::languages($model);
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-update">

    <?= '<?= ' ?>$this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
