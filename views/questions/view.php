<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model api\Questions */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Questions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container">
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane auctions fade show active" id="nav-auctions" role="tabpanel" aria-labelledby="nav-auctions-tab">
            <div class="auctions-header">
                <div class="row align-items-center">
                    <div class="col-lg-3">
                        <h3 class="auctions-title"><?= $this->title; ?></h3>
                    </div>
                </div>
            </div>

            <h1><?= Html::encode($this->title) ?></h1>

            <?php if(!$model->answer && (Yii::$app->user->id == $model->orgazation->user_id)): ?>
                <p>
                    <?= Html::a(Yii::t('app', 'Answer'), ['answer', 'id' => $model->unique_id], ['class' => 'btn btn-primary']) ?>
                </p>
            <?php endif; ?>
            <div class="well">
                <h4>
                    <span class="question-date"><?=Yii::t('app', 'Question date'); ?>: <?=Yii::$app->formatter->asDatetime($model->created_at); ?></span>
                    <br/>
                    <span class="question-target">
                    <?= Yii::t("app", "Question"); ?>:
                        <?= Yii::t('app', $model->questionOf == 'tender' ? 'of auction' : 'of item'); ?><br>
                    <b><?= $model->targetName; ?></b>
                </span>
                    <br>
                    <br>
                    <span class='question-title'><?=Yii::t('app', 'Question title');?>: <?=$model->title; ?></span>
                    <span class='lead question-type is_debug'><?=$model->questionOf; ?></span>
                </h4>
                <p><span class='question-description'><?=Yii::t('app', 'Question description'); ?>: <?=$model->description; ?></span></p>
                <?php if($model->answer): ?>

                    <p><span class='answer-date'><?=Yii::t('app', 'Date answered'); ?>: <?=Yii::$app->formatter->asDatetime($model->dateAnswered); ?></span></p>
                    <p><?= Yii::t('app', 'Відповідь на питання');?>: <span class="lead question-answer"><?=$model->answer; ?></span></p>
                <?php elseif($model->auction->lot && ($model->auction->lot->user_id === Yii::$app->user->id) && !$model->answer): ?>
                    <?=Html::a(Yii::t('app', 'Answer the question'), ['/questions/answer', 'id' => $model->unique_id], ['class' => 'btn btn-primary']); ?>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>