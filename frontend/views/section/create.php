<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Section */

$this->title = Yii::t('frontend/section', 'Create {modelClass}', [
    'modelClass' => 'Section',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend/section', 'Sections'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="section-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
