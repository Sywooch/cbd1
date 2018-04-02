<?php
use yii\helpers\Html;

?>

<div class="col-xs-12 col-sm-6 col-md-12">
    <div class="dopomoga__item--article">
        <h4><?= $model->title?></h4>
        <p><?= $model->short_text ?></p>
        <?= Html::a(Yii::t('app','Детальніше'),['/dopomoga/view/','slug' => $model->slug]); ?>
    </div>
</div>
