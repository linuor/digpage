<?php

namespace backend\assets;

use yii\web\AssetBundle;

class CkeditorAsset extends AssetBundle
{
    public $sourcePath = '@vendor/ckeditor/ckeditor';
    public $css = [];
    public $js = [
        'ckeditor.js',
    ];
    public $depends = [];
}
