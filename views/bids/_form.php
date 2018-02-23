<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model api\Bids */
/* @var $form yii\widgets\ActiveForm */
$model->accept = 1;
?>

<div class="bids-form row">
    <div class="col-md-12">
        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <?php if($model->scenario !== 'dgfInsider'): ?>
                <div class="col-md-4">
                    <?= $form->field($model, 'value_amount')->textInput() ?>
                </div>
            <?php endif; ?>
            <div class="col-md-12">
                <?=$form->field($model, 'accept')->checkbox(['label' => $model->getAttributeLabel('accept')]); ?>
            </div>
            <div class="col-md-12">
                <?=$form->field($model, 'oferta')->checkbox(['label' => $model->getAttributeLabel('oferta')]); ?>
            </div>
            <div class="col-md-12">
                <?= Html::submitButton(Yii::t('app', $model->isNewRecord ? 'Create' : 'Save'), ['class' => 'btn btn-success', 'style' => 'margin-top: 25px', 'id' => 'bid-save-btn'])?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

        <?php if(!$model->isNewRecord):?>
            <?= DetailView::widget([
                'model' => $model->lot,
                'attributes' => [
                    'num',
                    'name',
                    'description',
                    'bidding_date',
                    'auction_date',
                    'step',
                    'address',
                    'notes',
                    'start_price',
                ],
            ]) ?>

        <?php endif; ?>

    </div>
</div>