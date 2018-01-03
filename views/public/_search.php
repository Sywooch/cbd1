<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\search\Auctions */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="auctions-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'unique_id') ?>

    <?= $form->field($model, 'access_token') ?>

    <?= $form->field($model, 'awardCriteria') ?>

    <?= $form->field($model, 'dateModified') ?>

    <?php // echo $form->field($model, 'title') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'tenderID') ?>

    <?php // echo $form->field($model, 'procuringEntity_id') ?>

    <?php // echo $form->field($model, 'procuringEntity_kind') ?>

    <?php // echo $form->field($model, 'procurementMethod') ?>

    <?php // echo $form->field($model, 'procurementMethodType') ?>

    <?php // echo $form->field($model, 'owner') ?>

    <?php // echo $form->field($model, 'value_amount') ?>

    <?php // echo $form->field($model, 'value_currency') ?>

    <?php // echo $form->field($model, 'value_valueAddedTaxIncluded') ?>

    <?php // echo $form->field($model, 'guarantee_amount') ?>

    <?php // echo $form->field($model, 'guarantee_currency') ?>

    <?php // echo $form->field($model, 'date') ?>

    <?php // echo $form->field($model, 'minimalStep_amount') ?>

    <?php // echo $form->field($model, 'minimalStep_currency') ?>

    <?php // echo $form->field($model, 'minimalStep_valueAddedTaxIncluded') ?>

    <?php // echo $form->field($model, 'enquiryPeriod_startDate') ?>

    <?php // echo $form->field($model, 'enquiryPeriod_endDate') ?>

    <?php // echo $form->field($model, 'tenderPeriod_startDate') ?>

    <?php // echo $form->field($model, 'tenderPeriod_endDate') ?>

    <?php // echo $form->field($model, 'auctionPeriod_startDate') ?>

    <?php // echo $form->field($model, 'auctionPeriod_endDate') ?>

    <?php // echo $form->field($model, 'auctionUrl') ?>

    <?php // echo $form->field($model, 'awardPeriod_startDate') ?>

    <?php // echo $form->field($model, 'awardPeriod_endDate') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
