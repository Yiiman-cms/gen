<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator system\modules\gen\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();
global $mName;
global $hasImage;
echo "<?php\n";

?>
use system\modules\filemanager\widget\MediaViewWidget;
use yii\helpers\Html;
use <?= $generator->indexWidgetType === 'grid' ? "yii\\grid\\GridView" : "yii\\widgets\\ListView" ?>;
<?= $generator->enablePjax ? 'use yii\widgets\Pjax;' : '' ?>

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

\system\widgets\topMenu\TopMenuWidget::addBtb(
'add',
<?= $generator->generateString('ثبت ' . Inflector::camel2words(StringHelper::basename($generator->CrudName))) ?>,
'success' ,
null ,
Yii::$app->Options->BackendUrl . '/<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>/default/create'
);
\system\widgets\backLang\backLangWidget::languages();

$this->title = <?= $generator->generateString(Inflector::camel2words(StringHelper::basename($generator->CrudName))) ?>.' ';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index">
    <div class="card card-nav-tabs">
        <div class="card-body ">
            <h3 class="text-center"><?= "<?= " ?>Html::encode($this->title) ?></h3>

            <div class="row">
                <div class="col-md-12 pull-right">

                    <?= $generator->enablePjax ? "    <?php Pjax::begin(); ?>\n" : '' ?>
                    <?php if (!empty($generator->searchModelClass)): ?>
                        <?= "    <?php " . ($generator->indexWidgetType === 'grid' ? "// " : "") ?>echo $this->render('_search', ['model' => $searchModel]); ?>
                    <?php endif; ?>



                    <?php if ($generator->indexWidgetType === 'grid'): ?>
                        <?= "<?= " ?>GridView::widget([
                        'dataProvider' => $dataProvider,
                        <?= !empty($generator->searchModelClass) ? "'filterModel' => \$searchModel,\n        'columns' => [\n" : "'columns' => [\n"; ?>
                        ['class' => 'yii\grid\SerialColumn'],
                        ['class' => '\system\lib\i18n\LanguageColumn'],
                        <?php
                        if ($hasImage) {
                            ?>
                            ['class' => \system\modules\gallery\grid\ImageColumn::className()],
                            <?php
                        }
                        ?>
                        <?php
                        $count = 0;
                        if (($tableSchema = $generator->getTableSchema()) === false) {
                            foreach ($generator->getColumnNames() as $name) {
                                if (++$count < 6) {
                                    echo "            '" . $name . "',\n";
                                } else {
                                    echo "            //'" . $name . "',\n";
                                }
                            }
                        } else {
                            foreach ($tableSchema->columns as $column) {
                                $format = $generator->generateColumnFormat($column);
                                if (++$count < 6) {
                                    if ($column->name == 'id' || $column->name == 'language' || $column->name == 'language_parent') {
                                        continue;
                                    }

                                    switch ($column->name) {
                                        case 'status':
                                            ?>
                                            [
                                            'attribute' => 'status' ,
                                            'format'=>'raw',
                                            'value'   => function ( $model ) {

                                            switch ( $model->status ) {
                                            case $model::STATUS_ACTIVE:
                                            return '<span style="color:green">انتشار یافته</span>';
                                                                                                 break;
                                                                                                 case $model::STATUS_DE_ACTIVE:
                                                                                                 return '<span
                                                style="color: red">بازبینی</span>';
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
                                            'value'     => function ( $model ) {
                                            return MediaViewWidget::widget(['attribute'=>'<?= $column->name ?>','model'=>$model,'count'=>1]);
                                            }
                                            ] ,
                                            <?php
                                            break;
                                        default:
                                            echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
                                    }

                                } else {
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
                                            case 10:
                                            return 'انتشار یافته';
                                            break;
                                            case 0:
                                            return 'بازبینی';
                                            break;
                                            }
                                            },
                                            ],
                                            <?php

                                            break;
                                        default:
                                            echo "            //'" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
                                    }
                                }
                            }
                        }
                        ?>

                        ['class' => 'system\lib\ActionColumn'],
                        ],
                        ]); ?>
                    <?php else: ?>
                        <?= "<?= " ?>ListView::widget([
                        'dataProvider' => $dataProvider,
                        'itemOptions' => ['class' => 'item'],
                        'itemView' => function ($model, $key, $index, $widget) {
                        return Html::a(Html::encode($model-><?= $nameAttribute ?>), ['view', <?= $urlParams ?>]);
                        },
                        ]) ?>
                    <?php endif; ?>
                    <?= $generator->enablePjax ? "    <?php Pjax::end(); ?>\n" : '' ?>
                </div>
            </div>


        </div>


    </div>
</div>
