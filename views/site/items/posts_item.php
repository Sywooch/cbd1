<?php
    use yii\helpers\Html;


?>

<h1><?=$model->h1_ru && $lang == 'ru'? $model->h1_ru : $model->h1?></h1>
<?php if($model->getLogo()): ?>
<img alt="UUB" width="100%" src="<?= $model->getLogo() ?>">
<?php endif; ?>
<p><?=$model->short_text_ru  && $lang == 'ru' ? $model->short_text_ru : $model->short_text?></p>
<div class="show-more">

    <?= Html::a(Yii::t('app','Detail'),['view','slug' => $model->slug,'lang' => $lang ],['class' =>'button'])?>
</div>
