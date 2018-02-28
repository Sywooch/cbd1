<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\models\OrganizerForm */
/* @var $form ActiveForm */
if(!Yii::$app->user->identity->confirmed_at){
    Yii::$app->session->setFlash('success', Yii::t('app', 'Очікується підтвердження реєстраційних даних зі сторони майданчика'));
}

$this->title = Yii::t('app', 'Дані про організацію');
$this->params['breadcrumbs'][] = $this->title;
$this->registerJS(<<<JS
    $('input[name="Profile[org_type]"]').on('change', function(e){
        var type = $(this).val();
        if(type == 'individual'){
            $('#profile-firma_full').parents('.form-group').hide();
            $('#profile-zkpo').parents('.form-group').hide();
            $('#profile-inn').parents('.form-group').show();
            $('#profile-passport_number').parents('.form-group').show();
        }
        else{
            $('#profile-inn').parents('.form-group').hide();
            $('#profile-zkpo').parents('.form-group').show();
            $('#profile-passport_number').parents('.form-group').hide();
            $('#profile-firma_full').parents('.form-group').show();
        }
        if(type === 'financial'){
            $('#profile-licensenumber').parents('.form-group').show();
            $('input[name="Profile[document]"]').parents('.row.align-items-center').show();
        }
        else{
            $('#profile-licensenumber').parents('.form-group').hide();
            $('input[name="Profile[document]"]').parents('.row.align-items-center').hide();
        }
    });
JS
);
?>

<section class="registration">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-xl-9 registration-block">

                <?php $form = ActiveForm::begin([
                    'options' => [
                        'enctype' => 'multipart/form-data',
                    ],
                    'id' => 'organizer-form',
                    'enableAjaxValidation' => true,
                    'enableClientValidation' => false,
                ]); ?>
                <div class="row justify-content-center">
                    <div class="col-9">
                        <h3 class="mb-4">Завершення реєстрації</h3>

                        <h4>Дані про організацію</h4>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Форма організації</label>
                            <?= $form->field($model, 'org_type',
                                [
                                    'options' => [
                                        'class' => 'form-group col-md-6',
                                    ]
                                ])->radioList([
                                'financial' => Yii::t('app', 'Фінансова організація'),
                                'entity' => Yii::t('app', 'Юридична особа'),
                                'individual' => Yii::t('app', 'Фізична особа'),
                            ],
                                [
                                    'item' => function ($index, $label, $name, $checked, $value) {
                                        $check = $checked ? ' checked="checked"' : '';
                                        return "<label class='btn custom-radio " . ($checked ? 'active' : '') . "'><input type='radio' name='$name' value='$value' $check>$label</label>";
                                    },
                                    'class' => 'btn-group',
                                    'data' => [
                                        'toggle' => 'buttons'
                                    ]
                                ])->label(false); ?>
                        </div>

                        <?= $form->field($model, 'firma_full', [
                            'options' => ['class' => 'form-group row', 'style' => $model->org_type === 'individual' ? 'display:none' : ''],
                            'labelOptions' => ['class' => 'col-md-4 col-form-label'],
                            'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                        ]); ?>

                        <?= $form->field(
                            $model,
                            'region',
                            [
                                'options' => ['class' => 'form-group row'],
                                'template' => '{label}<div class="col-md-6"><div class="form-sel">{input}{hint}{error}</div></div>',
                                'labelOptions' => [
                                    'class' => 'col-md-4 col-form-label'
                                ]
                            ])->dropDownList([
                            '' => Yii::t('app', 'Choose region'),
                            'Автономна республіка Крим' => 'Автономна республіка Крим',
                            'Вінницька область' => 'Вінницька область',
                            'Волинська область' => 'Волинська область',
                            'Дніпропетровська область' => 'Дніпропетровська область',
                            'Донецька область' => 'Донецька область',
                            'Житомирська область' => 'Житомирська область',
                            'Закарпатська область' => 'Закарпатська область',
                            'Запорізька область' => 'Запорізька область',
                            'Івано-Франківська область' => 'Івано-Франківська область',
                            'Київська область' => 'Київська область',
                            'Кіровоградська область' => 'Кіровоградська область',
                            'Луганська область' => 'Луганська область',
                            'Львівська область' => 'Львівська область',
                            'Миколаївська область' => 'Миколаївська область',
                            'Одеська область' => 'Одеська область',
                            'Полтавська область' => 'Полтавська область',
                            'Рівненська область' => 'Рівненська область',
                            'Сумська область' => 'Сумська область',
                            'Тернопільська область' => 'Тернопільська область',
                            'Харківська область' => 'Харківська область',
                            'Херсонська область' => 'Херсонська область',
                            'Хмельницька область' => 'Хмельницька область',
                            'Черкаська область' => 'Черкаська область',
                            'Чернівецька область' => 'Чернівецька область',
                            'Чернігівська область' => 'Чернігівська область',
                            'м. Київ' => 'м. Київ',
                            'м. Севастополь' => 'м. Севастополь',
                        ]); ?>

                        <?= $form->field($model, 'city', [
                            'options' => ['class' => 'form-group row'],
                            'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>',
                            'labelOptions' => ['class' => 'col-md-4 col-form-label']
                        ]); ?>

                        <?= $form->field($model, 'postal_code', [
                            'options' => ['class' => 'form-group row'],
                            'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>',
                            'labelOptions' => ['class' => 'col-md-4 col-form-label']
                        ]); ?>

                        <?= $form->field($model, 'f_address', [
                            'options' => ['class' => 'form-group row'],
                            'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>',
                            'labelOptions' => ['class' => 'col-md-4 col-form-label']
                        ]); ?>

                        <?= $form->field($model, 'phone', [
                            'options' => ['class' => 'form-group row'],
                            'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>',
                            'labelOptions' => ['class' => 'col-md-4 col-form-label']
                        ])->widget(MaskedInput::className(), [
                            'mask' => '+380999999999',
                        ]); ?>

                        <h4>Дані представника організації</h4>


                        <?= $form->field($model, 'member', [
                            'options' => ['class' => 'form-group row'],
                            'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>',
                            'labelOptions' => ['class' => 'col-md-4 col-form-label']
                        ])->label(Yii::t('app', 'ПІБ представника')); ?>

                        <?= $form->field($model, 'licenseNumber', [
                            'options' => ['class' => 'form-group row', 'style' => $model->org_type != 'financial' ? 'display:none' : ''],
                            'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>',
                            'labelOptions' => ['class' => 'col-md-4 col-form-label']
                        ]); ?>

                        <?= $form->field($model, 'inn', [
                            'options' => ['class' => 'form-group row', 'style' => $model->org_type != 'individual' ? 'display:none' : ''],
                            'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>',
                            'labelOptions' => ['class' => 'col-md-4 col-form-label']
                        ])->label(Yii::t('app', 'ІПН')); ?>

                        <?= $form->field($model, 'passport_number', [
                            'options' => ['class' => 'form-group row', 'style' => $model->org_type != 'individual' ? 'display:none' : ''],
                            'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>',
                            'labelOptions' => ['class' => 'col-md-4 col-form-label']
                        ])->label(Yii::t('app', 'Серія та номер паспорту')); ?>

                        <?= $form->field($model, 'zkpo', [
                            'options' => ['class' => 'form-group row', 'style' => $model->org_type != 'financial' ? 'display:none' : ''],
                            'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>',
                            'labelOptions' => ['class' => 'col-md-4 col-form-label']
                        ]); ?>
                        <hr>

                        <div class="row align-items-center" style="<?= $model->org_type == 'financial' ? '' : 'display:none'; ?>">
                            <div class="col-md-4"><p>Фінансова ліцензія</p></div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'document')->widget(FileInput::className(), [
                                    'pluginOptions' => [
                                        'showUpload' => false,
                                        'showCancel' => false,
                                        'showPreview' => false,
                                    ]
                                ])->label(false); ?>
                            </div>
                        </div>

                        <div class="row align-items-center">
                            <div class="col-md-4"><p>Інші документи</p></div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'documents[]')->widget(FileInput::className(), [
                                    'pluginOptions' => [
                                        'showUpload' => false,
                                        'showCancel' => false,
                                        'showPreview' => false,
                                    ]
                                ])->label(false); ?>
                            </div>
                        </div>

                        <div class="form-group row justify-content-end">
                            <div class="col-lg-8">
                                <?= Html::submitButton(Yii::t('app', 'Завершити реєстрацію'), ['class' => 'btn btn-warning']); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</section>
