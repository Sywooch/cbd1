<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\Files;

/* @var $this yii\web\View */
/* @var $model api\Bids */


$file = new \app\models\Files([
    'user_id' => Yii::$app->user->id,
    'bid_id' => $model->unique_id,
]);

if($model->apiAuction->licenseRequired && !$model->financialLicense){
    if(Yii::$app->user->identity->profile->org_type == 'entity'){
        Yii::$app->session->setFlash('danger', Yii::t('app', 'Необхідно завантажити підписане повідомлення про те, що ви не є боржником та/або поручителем за даним кредитним договором'));
    }
    else{
        Yii::$app->session->setFlash('danger', Yii::t('app', 'You must upload the financial license'));
    }
}

$this->title = Yii::t('app', 'Update {modelClass} № ', [
        'modelClass' => Yii::t('app','Bid'),
    ]) . $model->unique_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bids'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->unique_id, 'url' => ['view', 'id' => $model->unique_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');


?>
<div class="container">
    <div class="bids-header">
        <div class="row align-items-center">
            <div class="col-lg-3">
                <h3 class="bids-title"><?= $this->title; ?></h3>
            </div>
        </div>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data',
        ],
        'action' => Url::to(['upload-document', 'id' => $model->unique_id]),
    ]); ?>

    <div class="col-md-4">
        <?=$form->field($file, 'type')->dropDownList($file->bidDocumentTypes()); ?>
    </div>
    <div class="col-md-6">
        <?=$form->field($file, 'file')->widget(\kartik\file\FileInput::className(), [
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