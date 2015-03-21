<?php
namespace backend\assets;

use yii\web\AssetBundle;

class ArticleSortAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [];
    public $js = [
        'js/article_index.js'
    ];
    public $depends = [
        'backend\assets\AppAsset',
        'yii\web\JqueryAsset',
    ];
}
