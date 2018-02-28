<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/*
 * @var $this  yii\web\View
 * @var $form  yii\widgets\ActiveForm
 * @var $model dektrium\user\models\SettingsForm
 */

$this->title = Yii::t('app', 'Зміна паролю');
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
            <div class="row">
                <div class="col-4 col-offset-4">

                    <?php $form = ActiveForm::begin([
                        'id'          => 'account-form',
                        'options'     => ['class' => 'form-horizontal'],
                        'enableAjaxValidation'   => true,
                        'enableClientValidation' => false,
                    ]); ?>

                    <?= $form->field($model, 'current_password')->passwordInput() ?>

                    <?= $form->field($model, 'new_password')->passwordInput() ?>

                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('user', 'Save'), ['class' => 'btn btn-block btn-success']) ?><br>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>