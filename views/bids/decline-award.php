<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\file\FileInput;

$this->title = Yii::t('app', 'Uploading disqualification reason');

?>

<div class="upload-disqualification-document">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <span class="glyphicon glyphicon-th"></span>
            <strong><?= Html::encode($this->title) ?></strong>
        </div>
        <div class="panel-body">

            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']])?>

            <?=$form->field($model, 'description')->textarea(['rows' => 3]); ?>

            <?=$form->field($file, 'file')->widget(FileInput::className(), [
                'options' => ['multiple' => false],
                'pluginOptions' => [
                    'showUpload' => false,
                    'showPreview' => false,
                ]
            ]); ?>

            <?=Html::submitButton(Yii::t('app', 'Upload'), ['class' => 'btn btn-primary', 'id' => 'upload-disqualification-btn']); ?>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>