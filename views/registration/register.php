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
use yii\captcha\Captcha;
use yii\widgets\MaskedInput;

/**
 * @var yii\web\View              $this
 * @var dektrium\user\models\User $user
 * @var dektrium\user\Module      $module
 */

$js = <<< JS
    $('.show-password').on('click', function(e) {
      var self = $(this),
      input = self.parent().find('input');
      if(input.attr('type') === 'password'){
          input.attr('type', 'text');
      }
      else{
          input.attr('type', 'password');
      }
    });
JS;

$this->registerJs($js);


$this->title = Yii::t('user', 'Sign up');
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="registration py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 col-xl-9 registration-block">
                <div class="row justify-content-center">
                    <div class="col-md-9">
                        <h3 class="mb-4">Реєстрація нового користувача</h3>
                        <?php $form = ActiveForm::begin([
                            'id' => 'registration-form',
                            'enableAjaxValidation' => true,
                        ]); ?>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Роль користувача</label>
                            <div class="col-md-6 btn-group" data-toggle="buttons">
                                <label class="btn custom-radio">
                                    <?= Html::activeInput('radio', $model, 'role', ['value' => '2']);?> Учасник
                                </label>
                                <label class="btn custom-radio">
                                    <?= Html::activeInput('radio', $model, 'role', ['value' => '1']);?> Організатор
                                </label>
                            </div>
                        </div>

                        <?= $form->field($model, 'email', [
                            'options' => ['class' => 'form-group row'],
                            'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>',
                            'labelOptions' => ['class' => 'col-md-4']
                        ]); ?>

                        <?= $form->field($model, 'username', [
                            'options' => ['class' => 'form-group row'],
                            'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>',
                            'labelOptions' => ['class' => 'col-md-4'],
                            'inputOptions' => ['placeholder' => Yii::t('app', 'Латиницею, без спецсимволів'), 'class' => 'form-control']
                        ]); ?>

                        <?= $form->field($model, 'password', [
                            'options' => ['class' => 'form-group row'],
                            'template' => '{label}<div class="col-md-6 input-group">{input}<button type="button" class="show-password input-group-addon"><img src="/images/password-off.png"></button></div><div class="col-md-4"></div><div class="col-md-6">{hint}{error}</div>',
                            'labelOptions' => ['class' => 'col-md-4'],
                        ])->passwordInput(); ?>

                        <?= $form->field($model, 'repeatpassword', [
                            'options' => ['class' => 'form-group row'],
                            'template' => '{label}<div class="col-md-6 input-group">{input}<button type="button" class="show-password input-group-addon"><img src="/images/password-off.png"></button></div><div class="col-md-4"></div><div class="col-md-6">{hint}{error}</div>',
                            'labelOptions' => ['class' => 'col-md-4'],
                        ])->passwordInput(); ?>

                        <div class="form-group row">
                            <div class="col">
                                <?= $form->field($model, 'oferta', ['options' => ['class' => 'form-check mb-4']])->checkbox(['label' => $model->getAttributeLabel('oferta'), 'labelOptions' => ['class' => 'form-check-label', 'style' => 'padding-left:0']]); ?>
                                <p class="text-center">
                                    <?= Html::submitButton(Yii::t('app', 'Зареєструватися'), ['class' => 'btn btn-warning']); ?>
                                </p>
                            </div>
                        </div>
                        <?php $form->end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
