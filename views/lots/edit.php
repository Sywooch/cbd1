<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use api\Classifications;
use app\models\Files;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\jui\DatePicker;
use kartik\time\TimePicker;
use kartik\file\FileInput;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\models\Lots */
/* @var $form yii\widgets\ActiveForm */


if(!$model->bidding_date){
    $model->bidding_date = date('Y-m-d H:i:s', time());
}
$js = "
    $('#lots-start_price').on('keyup', function(){
        $('#dgf_sum').val($(this).val() / 100 * 5);
    });
";
$this->registerJs($js, 4);
$this->title = Yii::t('app', 'Updating lot') . ': ' . $model->name;
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

            <?php $form = ActiveForm::begin([
                'id' => 'edit-lot',
                'options' => ['enctype' => 'multipart/form-data'],
                'enableAjaxValidation' => true,
            ]); ?>

            <?= $form->field($model, 'name')->textInput(['maxlength' => 255])->label(Yii::t('app','LotName ID')) ?>

            <?= $form->field($model, 'num')->textInput(['maxlength' => 255])->label(Yii::t('app','LotNumber ID')) ?>

            <?=$form->field($model, 'dgfDecisionID'); ?>

            <?=$form->field($model, 'dgfDecisionDate');?>

            <?=$form->field($model, 'tenderAttempts')->dropDownList([
                '' => Yii::t('app', 'Not known'),
                '1' => Yii::t('app', '1'),
                '2' => Yii::t('app', '2'),
                '3' => Yii::t('app', '3'),
                '4' => Yii::t('app', '4'),
            ]); ?>

            <?= $form->field($model, 'description')->textarea(['maxlength' => 800, 'rows' => 6, 'cols' => 50]) ?>

            <?= $form->field($model, 'clarificationFile')->fileInput(['class' => 'form-control']); ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', $model->isNewRecord ? 'Create ID' : 'Update ID'), ['class' => 'btn btn-primary', 'id' => 'submit-auction-btn']) ?>
                <?= Html::a(Yii::t('app', 'Back to auction'), ['/public/view', 'id' => $model->apiAuction->unique_id], ['class' => 'btn btn-default', 'id' => 'auction-view-btn']); ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>