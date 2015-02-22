<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\assets\ArticleFormAsset;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $form yii\widgets\ActiveForm */

ArticleFormAsset::register($this);
?>

<div class="section-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'toc_mode')->dropDownList($model->getAllTocMode()) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'comment_mode')->dropDownList($model->getAllCommentMode()) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend/section', 'Create'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
