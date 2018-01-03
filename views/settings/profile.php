<?php

use yii\helpers\Html;

/*
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var dektrium\user\models\Profile $profile
 */

$this->title = Yii::t('user', 'Profile settings');
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="panel panel-primary">
	<div class="panel-heading"><span class="glyphicon glyphicon-user"></span><strong> <?= Html::encode($this->title) ?></strong></div>
	<div class="panel-body">

<table class="table table-striped table-bordered">
	<tr>
		<td><b><?= Yii::t('app', 'Full organization name') ?></b></td>
		<td><?= $model->firma_full ?></td>
	</tr>
	<tr>
		<td><b><?= Yii::t('app', 'INN') ?></b></td>
		<td><?= $model->inn?></td>
	</tr>
	<tr>
		<td><b><?= Yii::t('app', 'ZKPO') ?></b></td>
		<td><?= $model->zkpo ?></td>
	</tr>
	<tr>
		<td><b><?= Yii::t('app', 'Legal address') ?></b></td>
		<td><?= $model->u_address ?></td>
	</tr>
	<tr>
		<td><b><?= Yii::t('app', 'Personal address') ?></b></td>
		<td><?= $model->f_address?></td>
	</tr>
	<tr>
		<td><b><?= Yii::t('app', 'Member') ?></b></td>
		<td><?= $model->member?></td>
	</tr>
	<tr>
		<td><b><?= Yii::t('app', 'Phone') ?></b></td>
		<td><?= $model->phone?></td>
	</tr>
	<tr>
		<td><b><?= Yii::t('app', 'Fax') ?></b></td>
		<td><?= $model->fax?></td>
	</tr>
	<tr>
		<td><b><?= Yii::t('app', 'E-mail') ?></b></td>
		<td><?= $model->member_email?></td>
	</tr>
	<tr>
		<td><b><?= Yii::t('app', 'Site') ?></b></td>
		<td><?= $model->site ?></td>
	</tr>
	<tr>
		<td><b><?= Yii::t('app', 'Files') ?></b></td>
		<td><?= $model->files ?></td>
	</tr>
</table>

<?= Html::a(Yii::t('app', 'Edit'), ['profile-settings'], ['class'=>'btn btn-primary']) ?>

</div></div>
