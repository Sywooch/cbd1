<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\file\FileInput;

$this->title = Yii::t('app', 'Uploading contract');



$file = new \app\models\Files([
    'user_id' => Yii::$app->user->id,
    'bid_id' => $model->unique_id,
]);
$documentTypes = $file->bidDocumentTypes();

$procurementMethod = $model->apiAuction ? $model->apiAuction->procurementMethodType : $model->lot->procurementMethodType;

if($model->apiAuction->licenseRequired && !$model->financialLicense){
    if($model->user_id == Yii::$app->user->id){
        Yii::$app->session->setFlash('danger', Yii::t('app', 'You must upload the financial license'));
    }
    unset($documentTypes['financialLicense']);
}

?>
<div class="container">
    <div class="upload-contract">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <span class="glyphicon glyphicon-th"></span>
                <strong><?= Html::encode($this->title) ?></strong>
            </div>
            <div class="panel-body">
                <h3><?=Yii::t('app', 'Reupload bid documents'); ?></h3>

                <?php $form = ActiveForm::begin([
                    'options' => [
                        'enctype' => 'multipart/form-data',
                    ],
                ]); ?>

                <div class="col-md-4">
                    <?=$form->field($file, 'type')->dropDownList($documentTypes); ?>
                </div>
                <div class="col-md-6">
                    <?=$form->field($file, 'file')->widget(FileInput::className(), [
                        'options' => [],
                        'pluginOptions' => [
                            'showUpload' => false,
                            'showPreview' => false,
                        ]
                    ]); ?>
                </div>
                <div class="col-md-2">
                    <?= Html::submitButton(Yii::t('app', 'Upload'), ['id' => 'document-upload-btn', 'class' => 'btn btn-primary btn-block', 'style' => 'margin-top: 25px']); ?>
                </div>
                <?php ActiveForm::end();?>
            </div>
        </div>
    </div>
</div>