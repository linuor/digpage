<?php

use yii\helpers\Html;
use common\models\Section;
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

    <?= $form->field($model, 'toc_mode')->dropDownList(Section::getAllTocMode()) ?>

    <?= $form->field($model, 'comment_mode')->dropDownList(Section::getAllCommentMode()) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend/section', 'Publish'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
