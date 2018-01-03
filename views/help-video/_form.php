<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\models\HelpCategory;

/* @var $this yii\web\View */
/* @var $model app\models\HelpVideo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="help-video-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cat_id')->widget(Select2::className(),[
        'data' => ArrayHelper::map(HelpCategory::find()->all(), 'id', 'title')
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
