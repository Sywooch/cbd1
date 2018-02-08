<?php
use yii\helpers\Html;


?>
<?php if(!Yii::$app->user->can('org') && (strtotime($model->enquiryPeriod_endDate)) >= time()): ?>
<?=Html::a(Yii::t('app', 'Create question'), ['/questions/create', 'id' => $auction->unique_id], ['class' => 'btn btn-success pull-right', 'id' => 'create-question-btn']); ?>
<?php endif; ?>
<?php if(count($questions) > 0): ?>
    <?php foreach($questions as $n => $question): $n++; ?>
        <?php $item_id = explode(':', $question->title)[0];?>
        <div class="well">
            <h4>
                <span class="question-date"><?=Yii::t('app', 'Question date'); ?>: <b id="questions[<?=$n; ?>].date"><?=Yii::$app->formatter->asDatetime($question->created_at); ?></b></span>
                <br/>
                <span class="question-target">
                    <?= Yii::t("app", "Question"); ?>:
                    <?= Yii::t('app', $question->questionOf == 'tender' ? 'of auction' : 'of item'); ?><br>
                    <b><?= Html::encode($question->targetName); ?></b>
                </span>
                <br>
                <br>
                <span class='question-title <?=$n; ?>'><?=Yii::t('app', 'Question title');?>: <b id="questions[<?=$n; ?>].title"><?=Html::encode($question->title); ?></b></span>
                <span class='lead question-type is_debug'><?=$question->questionOf; ?></span>
            </h4>
            <p><span class='question-description <?=$n; ?>'><?=Yii::t('app', 'Question description'); ?>:  <b id="questions[<?=$n; ?>].description"><?= Html::encode($question->description); ?></b></span></p>
            <?php if($question->answer): ?>
                <p><span class='answer-date <?=$n; ?>' id="questions[<?=$n; ?>].answer-date"><?=Yii::t('app', 'Date answered'); ?>: <?=Yii::$app->formatter->asDatetime($question->updated_at); ?></span></p>
                <p><span class="lead question-answer <?=$n; ?>" id="questions[<?=$n; ?>].answer"><?= Html::encode($question->answer); ?></span></p>
            <?php elseif($auction->lot && ($auction->lot->user_id === Yii::$app->user->id) && !$question->answer): ?>
                <?=Html::a(Yii::t('app', 'Answer the question'), ['/questions/answer', 'id' => $question->unique_id], ['class' => 'btn btn-primary', 'id' => "question[{$item_id}].answer"]); ?>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="well"><h3><?=Yii::t('app', 'No questions'); ?></h3></div>
<?php endif; ?>
