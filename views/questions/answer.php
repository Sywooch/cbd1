<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model api\Questions */

$this->title = Yii::t('app', 'Answer the question');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Questions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->unique_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
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

            <h1><?= Html::encode($this->title) ?></h1>
            <h3><?=$model->title; ?></h3>
            <div class="well">
                <?=$model->description; ?>
            </div>

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>

        </div>
    </div>
</div>
