<?php

$flashes = Yii::$app->session->getAllFlashes();

?>
        <?php if(count($flashes)): ?>
            <?php foreach ($flashes as $type => $message): ?>
                <?php if(in_array($type, ['success', 'danger', 'warning'])): ?>
                    <div class="alert alert-<?= $type ?>">
                        <?= $message ?>
                    </div>
                <?php elseif(in_array($type, ['info'])): ?>
                    <?php Yii::$app->view->registerJs(<<<JS
            swal({
                title: '',
                text: '$message',
            });
JS
                    ); ?>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
