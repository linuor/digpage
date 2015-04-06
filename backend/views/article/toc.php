<?php

use yii\helpers\Html;
use backend\assets\ArticleSortAsset;

/* @var $this yii\web\View */
/* @var $sections array */
/* @var $rootId integer */
$this->title = Yii::t('backend/section', 'Sort Toc');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend/section', 'Sections'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
ArticleSortAsset::register($this);
?>
<div class="section-sort">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend/section', 'Back'), ['index'], ['class' => 'btn btn-success', 'id' => 'btn-back']) ?>
    </p>
    <div id="jstree"></div>
</div>
