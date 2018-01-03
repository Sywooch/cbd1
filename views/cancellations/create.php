<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model api\Cancellations */

$this->title = Yii::t('app', 'Auction cancelling');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Cancellations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <span class="glyphicon glyphicon-stats"></span>
            <strong>
                <?= Html::encode($this->title) ?>
            </strong>
        </div>
        <div class="panel-body">
            <div class="cancellations-create">

                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>

            </div>
        </div>
    </div>
</div>