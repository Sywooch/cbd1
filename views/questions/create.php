<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model api\Questions */

$this->title = Yii::t('app', 'Create question');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Questions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="questions-create container">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
