<?php

use yii\helpers\Html;

/*
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var dektrium\user\models\Profile $profile
 */

$user = Yii::$app->user->identity;
$this->title = Yii::t('user', 'Profile settings');
$this->params['breadcrumbs'][] = $this->title;
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

            <?php $form = \yii\widgets\ActiveForm::begin([
                'id' => 'profile-form',
                'options' => ['class' => 'form-horizontal'],
                'enableAjaxValidation'   => true,
                'enableClientValidation' => false,
                'validateOnBlur'         => false,
            ]); ?>

            <?= $form->field($model, 'firma_full') ?>
            <?= $form->field($model, 'inn') ?>
            <?= $form->field($model, 'zkpo') ?>
            <?= $form->field($model, 'u_address') ?>
            <?= $form->field($model, 'f_address') ?>
            <?= $form->field($model, 'member') ?>
            <?= $form->field($model, 'phone') ?>
            <?= $form->field($model, 'fax') ?>
            <?= $form->field($model, 'member_email') ?>
            <?= $form->field($model, 'site') ?>
            <?= $form->field($model, 'files') ?>

            <div class="form-group">
                <?= \yii\helpers\Html::submitButton(Yii::t('user', 'Save'), ['class' => 'btn btn-block btn-success']) ?><br>
            </div>
            <?php \yii\widgets\ActiveForm::end(); ?>
        </div>
    </div>
</div>