<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\file\FileInput;

$this->title = Yii::t('app', 'Uploading contract');

?>

<div class="upload-contract">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <span class="glyphicon glyphicon-th"></span>
            <strong><?= Html::encode($this->title) ?></strong>
        </div>
        <div class="panel-body">
            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']])?>
            <?=$form->field($model, 'file')->widget(FileInput::className(), [
                'options' => ['multiple' => false],
                'pluginOptions' => [
                    'showUpload' => false,
                    'showPreview' => false,
                ]
            ]); ?>
            <?=Html::submitButton(Yii::t('app', 'Upload'), ['class' => 'btn btn-primary', 'id' => 'upload-contract-btn']); ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
