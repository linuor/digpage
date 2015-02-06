<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Comment */

$this->title = Yii::t('backend/comment', 'Create {modelClass}', [
    'modelClass' => 'Comment',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend/comment', 'Comments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
