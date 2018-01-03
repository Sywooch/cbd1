<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use app\models\HelpCategory;

?>

<section class="dopomoga">
    <header class="dopomoga__menu container">
        <div class="row">
            <div class="col-md-5 col-md-offset-1">
                <div class="dopomoga__category dopomoga__category--client">
                    <div class="dopomoga__layover">
                        <h3><?= Html::a(Yii::t('app','ЗАМОВНИКАМ'),['/dopomoga/category/','slug' => 'zamovnikam']) ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-5 ">
                <div class="dopomoga__category dopomoga__category--shipper">
                    <div class="dopomoga__layover">
                        <h3><?= Html::a(Yii::t('app','ПОСТАЧАЛЬНИКАМ'),['/dopomoga/category/','slug' => 'postachalnikam']) ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <section class="dopomoga__content">




        <section class="dopomoga__content">
            <div class="container">
                <h3>НАВЧАЛЬНІ МАТЕРІАЛИ</h3>
                <div class="row">
                    <div class="dopomoga__video col-md-7">
                        <div class="row">
                            <?php foreach ($videos as $video) : ?>
                                <div class="col-xs-12 col-sm-6 col-md-6">
                                    <div class="dopomoga__item--video">
                                        <?php echo Html::tag('iframe',$video->link,['width'=>"100%", 'height'=>"200",'src' => $video->link,'frameborder' => "0",'allowfullscreen' => true]); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="dopomoga__articles col-md-4 col-md-offset-1">
                        <div class="row">
                            <?=
                            ListView::widget([
                                'dataProvider' => $dataProvider,
                                'itemView' => '_form/_index',

                                'layout' => "{items}",
                            ]);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>
