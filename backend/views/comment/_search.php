<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\CommentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="comment-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'section') ?>

    <?= $form->field($model, 'parent') ?>

    <?= $form->field($model, 'child_num') ?>

    <?= $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'comment_mode') ?>

    <?php // echo $form->field($model, 'comment_num') ?>

    <?php // echo $form->field($model, 'thumbsup') ?>

    <?php // echo $form->field($model, 'thumbsdown') ?>

    <?php // echo $form->field($model, 'content') ?>

    <?php // echo $form->field($model, 'ver') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend/comment', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('backend/comment', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
