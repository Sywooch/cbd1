<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\jui\DatePicker;
use kartik\time\TimePicker;
use app\models\Lots;

/* @var $this yii\web\View */
/* @var $model app\models\Lots */
/* @var $form yii\widgets\ActiveForm */


if(!$model->bidding_date){
    $model->bidding_date = date('Y-m-d H:i:s', time());
}
$js = <<<JS

    $('#lots-procurementmethodtype').on('change', function(){
        var mode = $(this).val();
        if(mode === 'dgfOtherAssets'){
            $('#lots-vdr-input').hide();
        }
        else{
            $('#lots-vdr-input').show();
        }
    });

    function recalculateSum(e, type = 'byPercent'){
        var startSum = $('#lots-start_price').val();

        //if($('#lots-nds').is(':checked')){
        //    var sum = startSum *= 0.8;
        //}
        //else{
            var sum = startSum;
        //}

        var result = sum *= 0.05;
        $('#dgf_sum').val(Math.round(result*Math.pow(10,2))/Math.pow(10,2));
        
        if(type == 'byPercent'){
            var minimalStep = startSum / 100 * $('#step-percent').val();
            $('#lots-step').val(Math.round(minimalStep*Math.pow(10,2))/Math.pow(10,2));            
        }
        else{
            var minimalStep = parseFloat($('#lots-step').val() / startSum * 100).toFixed(2);
            $('#step-percent').val(minimalStep);
        }
    }
    $('#lots-nds').on('change', recalculateSum);
    $('#lots-start_price').on('change', recalculateSum);
    $('#step-percent').on('change', recalculateSum);
    $('#lots-step').on('change', function(){ recalculateSum(false, 'byValue'); });
JS
;
$this->registerJs($js, 4);
?>

<div class="col-md-12">
    <?php if(!$model->apiAuction): ?>
        <h3><?=Yii::t('app', 'Auction details'); ?></h3>
    <?php else:?>
        <h3><?=Yii::t('app', 'Additional Auction Documents'); ?></h3>
    <?php endif; ?>
</div>
<div class="col-md-12">

    <?php $form = ActiveForm::begin([
        'id' => 'dynamic-form',
        'enableAjaxValidation' => true,
    ]); ?>

    <?php if(!$model->apiAuction): ?>

        <?=$form->field($model, 'ownerName')->textInput(); ?>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'name')->textInput(['maxlength' => 255])->label(Yii::t('app','LotName ID')) ?>
            </div>
            <div class="col-md-3">
                <?=$form->field($model, 'auction_date')->widget(DatePicker::className(),[
                    'dateFormat' => 'dd.MM.yyyy',
                    'options' => ['class' => 'form-control']
                ]); ?>
            </div>
            <div class="col-md-3">
                <?=$form->field($model, 'auction_time')->widget(TimePicker::className(),[
                    'pluginOptions' => [
                        'showSeconds' => false,
                        'showMeridian' => false,
                    ]
                ]);?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-3">
                <?=$form->field($model, 'procurementMethodType')
                    ->dropDownList(
                        ArrayHelper::merge(
                            ['' => Yii::t('app', 'Choose procurement type')],
                            Lots::$procurementMethodTypes
                        ),
                        ['class' => 'form-control']); ?>
            </div>
            <div class="col-sm-3">
                <?= $form->field($model, 'num')->textInput(['maxlength' => 255])->label(Yii::t('app','LotNumber ID')) ?>
            </div>
            <div class="col-sm-3">
                <?=$form->field($model, 'dgfDecisionID'); ?>
            </div>
            <div class="col-sm-3">
                <?=$form->field($model, 'dgfDecisionDate')->widget(DatePicker::className(), [
                    'options' => ['class' => 'form-control']
                ]);?>
            </div>
        </div>

        <?=$form->field($model, 'tenderAttempts')->dropDownList([
            '' => Yii::t('app', 'Not known'),
            '1' => Yii::t('app', '1'),
            '2' => Yii::t('app', '2'),
            '3' => Yii::t('app', '3'),
            '4' => Yii::t('app', '4'),
            '5' => Yii::t('app', '5'),
            '6' => Yii::t('app', '6'),
            '7' => Yii::t('app', '7'),
            '8' => Yii::t('app', '8'),
        ]); ?>

        <?= $form->field($model, 'description')->textarea(['maxlength' => true, 'rows' => 12, 'cols' => 50]) ?>

    <?php endif; ?>

    <div id="lots-vdr-input">
        <?= $form->field($model, 'vdr')->textInput(['maxlength' => 255]); ?>
    </div>

    <?=$form->field($model, 'passport')->textInput(['maxlength' => true]); ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?php if(!$model->apiAuction): ?>

        <div class="row">
            <div class="col-sm-3">
                <?= $form->field($model, 'start_price')->textInput() ?>
            </div>
            <div class="col-sm-3">
                <div class = "form-group">
                    <label><?= Yii::t("app", "The size of the guarantee fee"); ?></label>
                    <?= Html::input("text", "dgf_sum", $model->start_price/100*5, ["id"=> "dgf_sum", "class" => "form-control", "disabled" => true]) ?>
                </div>
            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'nds')->checkbox()  ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'step')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <label for="step"><?= Yii::t('app', 'In percents'); ?></label>
                    <?= Html::input('number', 'step', $model->step_percent, ['id' => 'step-percent', 'class' => 'form-control', 'min' => 1, 'max' => 100, 'step' => 0.01]); ?>
                </div>
            </div>
        </div>


        <?= $form->field($model, 'notes')->textarea(['maxlength' => 800, 'rows' => 6, 'cols' => 50]) ?>

        <?= $form->field($model, 'date')->hiddenInput(['value' => date('Y-m-d H:i:s')])->label(false) ?>

    <?php endif; ?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', $model->isNewRecord ? 'Create ID' : 'Update ID'), ['class' => 'btn btn-primary', 'id' => 'submit-auction-btn']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

