<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\Cancellations */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cancellations-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'reason')->dropdownList([
        '' => Yii::t('app', 'Choose reason'),
        'Згідно рішення виконавчої дирекції Фонду гарантування вкладів фізичних осіб' => 'Згідно рішення виконавчої дирекції Фонду гарантування вкладів фізичних осіб',
        'Порушення порядку публікації оголошення' => 'Порушення порядку публікації оголошення',
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' =>'btn btn-success', 'id' => 'create-cancellation-btn']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>