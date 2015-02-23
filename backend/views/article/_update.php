<?php

use yii\helpers\Html;
use backend\assets\ArticleFormAsset;

/* @var $this yii\web\View */
/* @var $model common\models\Section */

ArticleFormAsset::register($this);
$this->title = Yii::t('backend/section', 'Update {modelClass}: ', [
    'modelClass' => 'Section',
]) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend/section', 'Sections'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend/section', 'Update');
?>
<div class="section-update">
    
</div>
