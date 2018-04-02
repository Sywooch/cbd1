<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\HelpVideo */

$this->title = Yii::t('app', 'Create Help Video');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Help Videos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="help-video-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
