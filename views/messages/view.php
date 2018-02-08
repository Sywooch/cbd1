<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

$this->title = $model->id;
$this->params['breadcrumbs']['messages'] = $this->title;
?>


    <div class="message-detail">
        <div class="container">
            <div class="messages-header">
                <a class="text-info d-inline-block mb-2" href="/messages/index">&larr; Назад до заявок</a>
                <div class="row align-items-center">
                    <div class="col-lg-3">
                        <h3 class="messages-title">Повідомлення #<?=$model->id?></h3>
                    </div>
                    <div class="col-lg-9">
                        <nav class="navbar navbar-expand-md sticky-top navbar-dark">
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#messagesControls" aria-controls="messagesControls" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="messagesControls">
                                <div class="navbar-nav ml-auto">
                                    <?=Html::a('Видалити', Url::to(['/messages/delete', 'id' => $model->id]), [
                                        'class' => 'nav-item nav-link auctions-control-trash',
                                        'data-method' => 'post',
                                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?')])?>
                                </div>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <p><?=$model->notes?></p>
                </div>
            </div>
        </div>
    </div>
  </main>
