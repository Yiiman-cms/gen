<?php


use yii\helpers\Inflector;
use yii\helpers\StringHelper;

global $relations;
$hasStatus = false;
$columns = $generator->getColumnNames();
foreach ($columns as $attribute) {
    if ($attribute == 'status') {
        $hasStatus = true;
    }
}
/* @var $this yii\web\View */
/* @var $generator system\modules\gen\generators\crud\Generator */

/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}


echo "<?php\n";
global $hasImage;
?>
use system\modules\filemanager\widget\FileSelectorWidget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */


?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form">

    <?= "<?php " ?>$form = ActiveForm::begin(); ?>

    <?php

    if (!$hasStatus) {

        ?>
        <div class="row">
            <?= '<?php' ?>
            if(Yii::$app->controller->action->id =='update'){
            ?>
            <div class="viewLanguagebox">
                زبان های موجود:
                <?= '<?= ' ?>(new \system\lib\i18n\LanguageColumn())->renderDataCell($model,0,0) ?>
            </div>

            <?= '<?php' ?>
            }
            ?>

            <div class="col-md-3">
                <div class="row">
                    <div class="card card-nav-tabs" style="margin-top: 20px ">
                        <div class="card-body ">
                            <h4 class="text-center">ذخیره و انتشار</h4>
                            <div class="row">
                                <div class="col-md-12 pull-right">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success">ذخیره</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                if (!empty($relations)) {

                    foreach ($relations as $key => $rel) {
                        if ($rel['table'] == 'module_language') {
                            continue;
                        }
                        if (isset($rel['link']['language_parent'])) {
                            continue;
                        }
                        ?>
                        <div class="row">
                            <div class="card card-nav-tabs" style="margin-top: 20px ">
                                <div class="card-body ">
                                    <h4 class="text-center"> <?= str_replace(' ها', '', $rel['name']) ?></h4>
                                    <div class="row">
                                        <div class="col-md-12 pull-right">
                                            <?= '<?= $form->field( $model , \'' . array_keys($rel['link'])[0] . '\' )->widget(
								\kartik\select2\Select2::className() ,
								[
									\'data\'          => \yii\helpers\ArrayHelper::map(
										\system\modules\\' . $rel['module'] . '\models\\' . $rel['class'] . '::find()->where([\'language_parent\'=>null])->all() ,
										\'id\' ,
										\'title\'
									) ,
									\'pluginOptions\' => [\'dir\' => \'rtl\',\'placeholder\'=>\'لطفا انتخاب کنید\']
								]
							) ?>' ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }


                }

                ?>
                <?php
                if ($hasImage) {
                    ?>
                    $model->image_input_widget($form, 'درج تصویر', true, ['image', 'video'], '');
                    <?php
                }

                $relations = json_encode($relations);
                foreach ($columns as $relKey => $attribute) {

                    if (in_array($attribute, $safeAttributes)) {

                        switch ($attribute) {
                            case 'description':
                            case 'Description':
                            case 'descriptions':
                            case 'Descriptions':
                            case 'content':
                            case 'Content':
                            case 'Contents':
                            case 'contents':
                            case 'image':
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
                                echo "<?php";
                                ?>
                                $model->image_input_widget( $form , 'درج تصویر' );
                                <?php
                                echo "?>";
                                break;

                            default:
                                break;

                        }
                    }


                }

                ?>
            </div>
            <div class="col-md-9">
                <div class="card card-nav-tabs" style="margin-top: 20px ">
                    <div class="card-body ">
                        <h4 class="text-center">مشخصات</h4>
                        <div class="row">
                            <div class="col-md-12 pull-right">
                                <?php
                                foreach ($columns as $attribute) {
                                    if ($attribute == 'language') {
                                        continue;
                                    }
                                    if ($attribute == 'language_parent') {
                                        continue;
                                    }

                                    if (strpos($relations, $attribute)) {

                                        continue;

                                    }
                                    if (in_array($attribute, $safeAttributes)) {

                                        echo "    <div class=\"col-md-6\"><?= " . $generator->generateActiveField(
                                                $attribute
                                            ) . " ?></div>\n\n";
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php


    } else {

        ?>

        <div class="row">
            <div class="col-md-3">
                <div class="row">
                    <div class="card card-nav-tabs">
                        <div class="card-body ">
                            <h4 class="text-center">وضعیت
                                انتشار <?= str_replace(' ها', '', Inflector::camel2words(StringHelper::basename($generator->CrudName))) ?></h4>

                            <div class="row">
                                <div class="col-md-12 pull-right">
                                    <?= '<?= $form->field( $model , \'status\' )->widget(
											\kartik\select2\Select2::className() ,
											[
												\'data\'          =>
													[
														$model::STATUS_DE_ACTIVE  => \'منتشر شده\' ,
														$model::STATUS_ACTIVE   => \'در حال بازبینی\' ,
														
													] ,
												\'options\'       => [ \'dir\' => \'rtl\' ] ,
												\'pluginOptions\' => [ \'dir\' => \'rtl\' ] ,
												\'pluginEvents\'  => [
													"change"              => "function() {  }" ,
													"select2:opening"     => "function() {  }" ,
													"select2:open"        => "function() {  }" ,
													"select2:closing"     => "function() {  }" ,
													"select2:close"       => "function() {  }" ,
													"select2:selecting"   => "function() {  }" ,
													"select2:select"      => "function() {  }" ,
													"select2:unselecting" => "function() {  }" ,
													"select2:unselect"    => "function() {  }"
												]
											]
										) ?>' ?>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success">ذخیره</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                if (!empty($relations)) {

                    foreach ($relations as $key => $rel) {
                        if ($rel['table'] == 'module_language') {
                            continue;
                        }
                        if (isset($rel['link']['language_parent'])) {
                            continue;
                        }
                        ?>
                        <div class="row">
                            <div class="card card-nav-tabs" style="margin-top: 20px ">
                                <div class="card-body ">
                                    <h4 class="text-center"> <?= str_replace(' ها', '', $rel['name']) ?></h4>
                                    <div class="row">
                                        <div class="col-md-12 pull-right">
                                            <?= '<?= $form->field( $model , \'' . array_keys($rel['link'])[0] . '\' )->widget(
								\kartik\select2\Select2::className() ,
								[
									\'data\'          => \yii\helpers\ArrayHelper::map(
										\system\modules\\' . $rel['module'] . '\models\\' . $rel['class'] . '::find()->where(
											[ \'status\' => 1,\'language_parent\'=>null ]
										)->all() ,
										\'id\' ,
										\'title\'
									) ,
									\'pluginOptions\' => [\'dir\' => \'rtl\',\'placeholder\'=>\'لطفا انتخاب کنید\']
								]
							) ?>' ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }


                }

                ?>
                <?php


                $relations = json_encode($relations);
                foreach ($columns as $relKey => $attribute) {

                    if (in_array($attribute, $safeAttributes)) {

                        switch ($attribute) {
                            case 'description':
                            case 'Description':
                            case 'descriptions':
                            case 'Descriptions':
                            case 'content':
                            case 'Content':
                            case 'Contents':
                            case 'contents':
                            case 'image':
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
                                echo "<?php";
                                ?>
                                $model->image_input_widget( $form , 'درج تصویر' );
                                <?php
                                echo "?>";
                                break;
                            case 'language':
                            case 'language_parent':
                                echo '';
                                break;
                            default:
                                break;

                        }
                    }


                }

                ?>
            </div>
            <div class="col-md-9">
                <div class="card card-nav-tabs card-rtl">
                    <div class="card-body ">
                        <div class="col-md-12">
                            <h4 class="text-center">مشخصات</h4>
                            <div class="row">
                                <?php


                                $relations = json_encode($relations);
                                foreach ($columns as $relKey => $attribute) {


                                    if (strpos($relations, $attribute)) {

                                        continue;

                                    }
                                    if ($attribute == 'status') {
                                        continue;
                                    }
                                    if ($attribute == 'id') {
                                        continue;
                                    }
                                    if ($attribute == 'hash') {
                                        continue;
                                    }
                                    if ($attribute == 'language') {
                                        continue;
                                    }
                                    if ($attribute == 'language_parent') {
                                        continue;
                                    }

                                    if (in_array($attribute, $safeAttributes)) {

                                        switch ($attribute) {
                                            case 'slug':
                                                echo '<div class="col-md-6"><?= SlugField::run($form, $model) ?></div>';
                                                break;
                                            case 'description':
                                            case 'Description':
                                            case 'descriptions':
                                            case 'Descriptions':
                                            case 'content':
                                            case 'Content':
                                            case 'Contents':
                                            case 'contents':
                                            case 'image':
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
//												    continue ;

                                                echo '';
                                                break;
                                            default:
                                                if (!empty($columns[($relKey + 1)])) {

                                                    echo "    <div class=\"col-md-6\"><?= " . $generator->generateActiveField(
                                                            $attribute
                                                        ) . " ?></div>\n\n";
                                                } else {
                                                    echo "    <div class=\"col-md-12\"><?= " . $generator->generateActiveField(
                                                            $attribute
                                                        ) . " ?></div>\n\n";
                                                }
                                        }
                                    } else {
                                        switch ($attribute) {
                                            case 'description':
                                            case 'Description':
                                            case 'descriptions':
                                            case 'Descriptions':
                                            case 'content':
                                            case 'Content':
                                            case 'Contents':
                                            case 'contents':
                                                echo "    <div class=\"col-md-12\"><?= " . $generator->generateActiveField(
                                                        $attribute
                                                    ) . " ?></div>\n\n";
                                                break;
                                            case 'slug':
                                                echo '<div class="col-md-6"><?= SlugField::run($form, $model) ?></div>';
                                                break;
                                        }
                                    }


                                }

                                ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <?php

    }
    ?>


    <?= "<?php " ?>ActiveForm::end(); ?>

</div>
