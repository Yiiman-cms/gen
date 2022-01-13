<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace YiiMan\gen;

use yii\web\AssetBundle;

/**
 * This declares the asset files required by Gii.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class GiiAsset extends AssetBundle
{
    public $sourcePath = '@vendor/yiiman/yii-module-gen/src/assets';
    public $css = [
        'main.css',
        'css/fontiran.css',
        'css/style.css',
    ];
    public $js = [
        'gii.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'YiiMan\gen\TypeAheadAsset',
    ];
}
