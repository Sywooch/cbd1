<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model api\Bids */

$this->title = Yii::t('app', 'Create Bid');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bids'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bids-create">


    <div class="bids-update container">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <?=$this->title; ?>
            </div>
            <div class="panel-body">
                <div class="well">

                <?=$this->render('_form', ['model' => $model]); ?>

                </div>
            </div>
        </div>
    </div>
</div>
