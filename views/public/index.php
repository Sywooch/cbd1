<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;
use app\models\Lots;
use api\Auctions;

/* @var $this yii\web\View */
/* @var $searchModel api\search\Auctions */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Auctions');
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs(<<<JS
    $('#index-form *').on('change', function(){
       $('#index-form').submit(); 
    });
JS

);
?>


<main class="site-content">
    <section class="publications">
        <div class="container">
            <h2 class="publications-title">Публікації</h2>
            <div class="publications-search">
                <?php $form = ActiveForm::begin([
                    'method' => 'get',
                    'action' => ['/public'],
                    'id' => 'index-form'
                ])?>

                <div class="input-group mb-2">
                    <?= Html::activeInput('text', $searchModel, 'main_search',
                        ['class' => 'form-control', 'placeholder'=>'Назва аукціону, назва або код ЄДРПОУ компанії'])?>
                    <span class="input-group-btn">
                        <?= Html::submitButton(Html::img('/images/icon-search.png'), ['class' => 'btn btn-primary', 'id' => 'public-search-btn'])?>
                    </span>
                </div>
                <input class="link-secondary mb-3 p-0" data-toggle="collapse" data-target="#expandedSearch" type="button" value="Розширений пошук">
                <img src="/images/icon-plus.png" alt="">
                <div class="collapse <?= $searchModel->isClear() ? '' : 'show'?>" id="expandedSearch">
                    <div class="row ">
                        <div class="col-md-7">
                            <div class="form-row">
                                <div class="form-group col-md-6 col-lg-4 mb-0">
                                    <?=Html::activeDropDownList($searchModel, 'status',
                                        array_merge(['' => Yii::t('app' ,'Статус')], (new Auctions())->statusNames),
                                        [
                                            'class' => 'form-control',
                                            'id' => 'search-filter-status'
                                        ]) ?>
                                </div>
                                <div class="form-group col-md-6 col-lg-4 mb-0">
                                    <?=Html::activeDropDownList($searchModel, 'type',
                                        array_merge(['' => Yii::t('app' ,'Тип аукціону')], Lots::$procurementMethodTypes),
                                        [
                                            'class' => 'form-control',
                                            'id' => 'search-filter-type'
                                        ]) ?>
                                </div>
                                <div class="form-group col-md-6 col-lg-4 mb-0">
                                    <?= Html::activeInput('text', $searchModel, 'region',
                                        ['class' => 'form-control', 'placeholder'=>'Назва регіону', 'id' =>'search-filter-region'])?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-row">
                                <div class="form-group col-md-6 mb-0">
                                    <?= Html::activeInput('text', $searchModel, 'cav',
                                        ['class' => 'form-control', 'placeholder'=>'CAV', 'id' =>'search-filter-region'])?>
                                </div>

                                <div class="form-group col-md-6 mb-0">
                                    <?= Html::activeInput('text', $searchModel, 'org_name',
                                        ['class' => 'form-control', 'placeholder'=>'Організатор', 'id' =>'search-filter-region'])?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row no-gutters">
                        <div class="col mt-3">
                            <a class="link-secondary" href="/public" id="reset-btn" name="search-clear">Очистити фільтр</a>
                        </div>
                    </div>
                </div>
                <?php ActiveForm::end();?>
            </div>

            <section class="content bordered">
                <?= ListView::widget([
                    'dataProvider'=>$dataProvider ,
                    'itemView' => '_forms/_auctions',
                    'layout' => "{items}",
                ]) ?>

            </section>
            <?= \yii\widgets\LinkPager::widget([
                'pagination'=>$dataProvider->pagination,
                'prevPageLabel' => '&larr;',
                'nextPageLabel' => '&rarr;',
                'options' => [
                    'class' => 'pagination justify-content-center',
                ],

                // Customzing CSS class for pager link
                'linkOptions' => ['class' => 'page-link'],
                'activePageCssClass' => 'page-item active',
                'disabledPageCssClass' => 'hidden page-item',

            ]); ?>
        </div>
    </section>
</main>