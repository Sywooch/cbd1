<?php
use yii\helpers\Html;
?>

<div class="row my-4">
    <div class="col-md-3">
        <?php if($model->picture){
            echo Html::img(['/uploads/posts/' . $model->picture], ['class' => 'img-fluid']);
        }else{
            echo Html::img(['/images/img-article.jpg'], ['class' => 'img-fluid']);
        }?>
    </div>
    <div class="col-md-9">
        <div class="row">
            <div class="news-item col-12">
        <h3 class="news-item-title"><?= Html::a($model->h1_ru && $lang == 'ru'? $model->h1_ru : $model->h1,
                ['/site/view', 'name' => $model->slug,'lang' => $lang ])?></h3>
        <time class="news-date"><?= date('d/m/Y', $model->created_at)?></time>

        <p><?=$model->short_text_ru && $lang == 'ru'? $model->short_text_ru : $model->short_text?></p>

        <?= Html::a(Yii::t('app','Detail'),
            ['/site/view', 'name' => $model->slug,'lang' => $lang ], ['class' =>'btn btn-outline-primary'])?>
            </div>
        </div>
    </div>
</div>
