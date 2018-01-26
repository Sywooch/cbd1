<?php

use yii\widgets\ActiveForm;
use api\Classifications;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\typeahead\Typeahead;


$this->title = Yii::t('app', 'Add auction item');
if($model->isNewRecord){
    $model->quantity = 1;
    $model->unit_code = 'LO';
    $classification = Classifications::findOne(['id' => '07000000-9']);
    $model->classification_id = $classification ? $classification->id . ' - ' . $classification->description : null;
}
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
            <?php $form  = ActiveForm::begin(); ?>
            <?=$form->field($model, "description")->textArea(['rows' => 2]); ?>
            <div class="row">
                <div class="col-sm-4">
                    <?=$form->field($model, "quantity")->input('number', ['min' => 1, 'max' => 9999999]); ?>
                </div>
                <div class="col-sm-4">
                    <?=$form->field($model, "classification_id")->widget(Typeahead::className(),[
                        'options' => ['placeholder' => Yii::t('app', 'Choose')],
                        'pluginOptions' => [
                            'highlight'=>true,
                        ],
                        'dataset' => [
                            [
                                'display' => 'value',
                                'remote' => [
                                    'url' => Url::to(['/lots/classifications']) . '?code=%QUERY',
                                    'wildcard' => '%QUERY'
                                ],
                            ],
                        ],
                    ]); ?>
                </div>
                <div class="col-sm-4">
                    <?=$form->field($model, "unit_code")->dropDownList(array_merge(['' => Yii::t('app', 'Choose')], $model->units())); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <?=$form->field($model, "address_region")->dropDownList([
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
                        'місто Київ' => 'місто Київ',
                        'місто Севастополь' => 'місто Севастополь',
                    ]); ?>
                </div>
                <div class="col-sm-4">
                    <?=$form->field($model, "address_postalCode"); ?>
                </div>
                <div class="col-sm-4">
                    <?=$form->field($model, "address_locality"); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <?=$form->field($model, "address_streetAddress"); ?>
                </div>
                <div class="col-sm-4">
                    <?=$form->field($model, "location_latitude"); ?>
                </div>
                <div class="col-sm-4">
                    <?=$form->field($model, "location_longitude"); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">

                    <?=$form->field($model, "address_countryName")->hiddenInput(['value' => 'Україна'])->label(false); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <?=Html::a(Yii::t('app', 'Cancel'), ['/lots/update', 'id' => $lot->id], ['class' => 'btn btn-danger']); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('app', 'Create ID'), ['class' => 'btn btn-primary pull-right', 'id' => 'btn-item-add']); ?>
                    </div>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
