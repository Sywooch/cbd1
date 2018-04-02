<?php
use yii\helpers\Html;

?>

            <div class="col-md-5 col-md-offset-1">
                <div class="dopomoga__category dopomoga__category--client">
                    <div class="dopomoga__layover">
                        <h3><?=Html::a($model->title,['/dopomoga/category/','slug' => $model->slug]);?></h3>
                    </div>
                </div>
            </div>


