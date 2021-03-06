<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\file\FileInput;

$this->title = Yii::t('app', 'Uploading contract');

?>

<div class="container">
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane auctions fade show active" id="nav-auctions" role="tabpanel" aria-labelledby="nav-auctions-tab">
            <div class="auctions-header">
                <div class="row align-items-center">
                    <div class="col-lg-3">
                        <h3 class="auctions-title"><?= $this->title; ?></h3>
                    </div>
                </div>
            </div>
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
