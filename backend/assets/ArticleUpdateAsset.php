<?php
namespace backend\assets;

use yii\web\AssetBundle;

class ArticleUpdateAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [];
    public $js = [
        'js/article_update.js'
    ];
    public $depends = [
        'backend\assets\AppAsset',
        'backend\assets\CkeditorAsset',
        'yii\web\JqueryAsset',
    ];
}
