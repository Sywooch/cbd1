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

<div class="panel panel-default">
	<div class="panel-heading">
		<?= Html::encode($this->title) ?>
	</div>
	<div class="panel-body">
		<?php $form = \yii\widgets\ActiveForm::begin([
			'id' => 'profile-form',
			'options' => ['class' => 'form-horizontal'],
			'fieldConfig' => [
			'template' => "{label}\n<div class=\"col-lg-9\">{input}</div>\n<div class=\"col-sm-offset-3 col-lg-9\">{error}\n{hint}</div>",
			'labelOptions' => ['class' => 'col-lg-3 control-label'],
			],
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
				<div class="col-lg-offset-3 col-lg-9">
					<?= \yii\helpers\Html::submitButton(Yii::t('user', 'Save'), ['class' => 'btn btn-block btn-success']) ?><br>
				</div>
			</div>
			<?php \yii\widgets\ActiveForm::end(); ?>
		</div>
	</div>