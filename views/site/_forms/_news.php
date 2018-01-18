<?php
/**
 * Created by PhpStorm.
 * User: ivakhnov
 * Date: 09.02.17
 * Time: 12:43
 */

use yii\helpers\Html;

Yii::$app->request->url;

?>

<article class = "blog-wrap">

    <header class="page-header blog-title">
        <div class="post-meta">
            <ul class="list-inline">
                <li><span class="fa fa-calendar"></span><a><?= Yii::$app->formatter->format($model->created_at ,'date') ?> </a></li>
            </ul>
        </div>
    </header>
    <div class="post-desc">
        <h2><?= $model->title ?></h2>
        <p><?= $model->priview ?> </p>
        <span class="fa fa-category"></span>
        <div id="sidebar">
            <div class="widget">
            </div>
        </div>

        <?= Html::a(Yii::t('app','Details'), ['view', 'slug' => $model->slug], ['class' => 'btn btn-default'])?>
    </div>

</article>
