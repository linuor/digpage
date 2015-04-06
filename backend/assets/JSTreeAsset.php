<?php

namespace backend\assets;

use yii\web\AssetBundle;

class JSTreeAsset extends AssetBundle
{
    public $sourcePath = '@vendor/vakata/jstree/dist';
    public $css = [
        'themes\default\style.min.css',
    ];
    public $js = [
        'jstree.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
