<?php

use yii\helpers\Html;
use yii\widgets\ListView;

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
