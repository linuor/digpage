<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Section */

$this->title = Yii::t('frontend/section', 'Update {modelClass}: ', [
    'modelClass' => 'Section',
]) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend/section', 'Sections'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('frontend/section', 'Update');
?>
<div class="section-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
