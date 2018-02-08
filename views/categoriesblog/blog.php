<?php

use yii\widgets\ListView;
use app\models\Categoriesblog;
use app\widgets\Categories as CategoriesWidget;
use yii\helpers\Html;
use app\widgets\pagesOut;

?>

<section class="news py-5">
    <div class="container">
        <h2 class="news-title">Новини</h2>

        <!--Виджет вывода записей-->
        <?= ListView::widget([
            'dataProvider'=>$dataProvider ,
            'itemView' => 'items/posts_item',
            'layout' => "{items}\n",
            'viewParams' => [
                'lang' => $lang
            ]
        ]) ?>
    </div>
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
    'disabledPageCssClass' => 'page-item',

    'lastPageCssClass' => 'mylast',
]); ?>

