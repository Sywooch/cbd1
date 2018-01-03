<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\Questions */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="questions-form container">

    <?php $form = ActiveForm::begin(); ?>

    <?php if($model->scenario == 'create'): ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?php elseif($model->scenario == 'answer'): ?>

    <?= $form->field($model, 'answer')->textarea(['rows' => 6]) ?>

    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Answer'), ['class' => ($model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'), 'id' => 'create-question-btn']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
