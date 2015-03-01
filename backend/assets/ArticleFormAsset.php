<?php
namespace backend\assets;

use yii\web\AssetBundle;

class ArticleFormAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [];
    public $js = [
        'js/article_form.js'
    ];
    public $depends = [
        'backend\assets\CkeditorAsset',
        'yii\web\JqueryAsset',
    ];
}
