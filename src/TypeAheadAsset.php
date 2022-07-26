<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace YiiMan\gen;

use yii\web\AssetBundle;

/**
 * Declares the asset files for jQuery 'typeahead' plugin.
 *
 * @see http://twitter.github.io/typeahead.js/
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class TypeAheadAsset extends AssetBundle
{
    public $sourcePath = '@bower/bs-typeahead/js/';
    public $js = [
        'bootstrap-typeahead.js',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
