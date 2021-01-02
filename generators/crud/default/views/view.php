<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator system\modules\gen\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$urlId = $generator->generateUrlModelID();
global $mName;
global $hasImage;
echo "<?php\n";
?>
use system\modules\filemanager\widget\MediaViewWidget;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */


\system\widgets\topMenu\TopMenuWidget::addBtb(
'add',
<?= $generator->generateString('ثبت ' . Inflector::camel2words(StringHelper::basename($generator->CrudName))) ?>,
'success' ,
null ,
Yii::$app->Options->BackendUrl . '/<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>/default/create'
);


\system\widgets\topMenu\TopMenuWidget::addBtb(
'edit',
<?= $generator->generateString('ویرایش این مورد') ?>,
'info' ,
null ,
Yii::$app->Options->BackendUrl . '/<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>/default/update?id='.<?= $urlId ?>
);


\system\widgets\topMenu\TopMenuWidget::addBtb(
'delete',
<?= $generator->generateString('حذف این مورد') ?>,
'danger' ,
null ,
Yii::$app->Options->BackendUrl . '/<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>/default/delete?id='.<?= $urlId ?>
);


$this->title = Yii::t('<?= $generator->messageCategory ?>','<?= $generator->CrudName . ': ' ?> '.$model-><?= $generator->getNameAttribute() ?>);
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::camel2words(StringHelper::basename($mName))) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\system\widgets\backLang\backLangWidget::languages($model);

?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-view">
    <div class="container">
        <div class="jumbotron">
            <div class="viewLanguagebox">
                زبان های ست شده:
                <?= '<?=' ?> (new \system\lib\i18n\LanguageColumn())->renderDataCell($model,0,0) ?>
            </div>
            <div class="card card-nav-tabs">
                <div class="card-body ">
                    <h3 class="text-center"><?= "<?= " ?>Html::encode($this->title) ?></h3>
                    <div class="row">
                        <div class="col-md-12 pull-right">
                            <?= "<?= " ?>DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                            <?php
                            if (($tableSchema = $generator->getTableSchema()) === false) {
                                foreach ($generator->getColumnNames() as $name) {
                                    if ($name == 'id') {
                                        continue;
                                    }
                                    echo "            '" . $name . "',\n";
                                }
                            } else {
                                foreach ($generator->getTableSchema()->columns as $column) {
                                    $format = $generator->generateColumnFormat($column);
                                    if ($column->name == 'id' || $column->name == 'language' || $column->name == 'language_parent') {
                                        continue;
                                    }
                                    switch ($column->name) {
                                        case 'status':
                                            ?>
                                            [
                                            'attribute' => 'status' ,
                                            'value'   => function ( $model ) {
                                            /**
                                            * @var $model \common\models\Neighbourhoods
                                            */
                                            switch ( $model->status ) {
                                            case 1:
                                            return 'فعال';
                                            break;
                                            case 0:
                                            return 'غیرفعال';
                                            break;
                                            }
                                            },
                                            ],
                                            <?php
                                            break;
                                        case 'image':
                                        case 'img':
                                        case 'Image':
                                        case 'images':
                                        case 'Images':
                                        case 'logo':
                                        case 'Logo':
                                        case 'file':
                                        case 'File':
                                        case 'files':
                                        case 'Files':
                                        case 'avatar':
                                        case 'Avatar':
                                        case 'picture':
                                        case 'Picture':
                                        case 'pictures':
                                        case 'Pictures':
                                            ?>
                                            [
                                            'attribute' => '<?= $column->name ?>' ,
                                            'format'    => 'raw' ,
                                            'value'     => function ( $model )
                                            {
                                            return MediaViewWidget::widget(['attribute'=>'<?= $column->name ?>','model'=>$model]);
                                            }
                                            ] ,
                                            <?php
                                            break;
                                        default:
                                            echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
                                    }
                                }
                            }
                            if ($hasImage) {
                                ?>
                                [
                                'attribute' => 'image',
                                'format' => 'raw',
                                'value' => function ($model) {
                                return MediaViewWidget::widget(['attribute' => 'image', 'model' => $model]);
                                }
                                ],
                                <?php
                            }
                            ?>
                            ],
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
