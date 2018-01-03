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

$this->title = Yii::t('user', 'Account settings');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel panel-primary">
    <div class="panel-heading"><span class="glyphicon glyphicon-flash"></span><strong> Зміна пароля</strong></div>
    <div class="panel-body">


<?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>

                <?php $form = ActiveForm::begin([
                    'id'          => 'account-form',
                    'options'     => ['class' => 'form-horizontal'],
                    'fieldConfig' => [
                        'template'     => "{label}\n<div class=\"col-lg-4\">{input}</div>\n<div class=\"col-sm-offset-2 col-lg-1\">{error}\n{hint}</div>",
                        'labelOptions' => ['class' => 'col-lg-4 control-label'],
                    ],
                    'enableAjaxValidation'   => true,
                    'enableClientValidation' => false,
                ]); ?>
                
                <?= $form->field($model, 'current_password')->passwordInput() ?>

                <?= $form->field($model, 'new_password')->passwordInput() ?>

                <div class="form-group">
                    <div class="col-lg-offset-4 col-lg-4">
                        <?= Html::submitButton(Yii::t('user', 'Save'), ['class' => 'btn btn-block btn-success']) ?><br>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>
</div></div>
