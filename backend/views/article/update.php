<?php

use backend\assets\ArticleUpdateAsset;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $sections array */

ArticleUpdateAsset::register($this);
$this->title = Yii::t('backend/section', 'Update {modelClass}: ', [
    'modelClass' => 'Section',
]) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend/section', 'Sections'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend/section', 'Update');
?>
<div class="section-update">
<?=
$this->render('_update', [
    'sections' => $sections,
    'rootId' => $model->id,
]);
?>
</div>
