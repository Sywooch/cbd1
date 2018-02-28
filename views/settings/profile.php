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
            <div class="form-group">
                <?= Html::a(Yii::t('app', 'Редагувати'), ['/registration/organizer'], ['class' => 'btn btn-primary']); ?>
                <?= Html::a(Yii::t('app', 'Змінити пароль'), ['/settings/account'], ['class' => 'btn btn-default']); ?>
            </div>

        </div>
    </div>
</div>
