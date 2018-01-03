<?php

use yii\helpers\Html;

/*
 * This file is part of the Dektrium project
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

/**
 *
 * @var yii\web\View
 * @var dektrium\user\models\User
 */

?>

<?php $this->beginContent('@dektrium/user/views/admin/update.php', ['user' => $user]) ?>

<table class="table"><tr>
        <td><b><?= Yii::t('app', 'Full organization name') ?></b></td>
        <td><?= $user->profile->firma_full ?></td>
    </tr>
    <tr>
        <td><b><?= Yii::t('app', 'INN') ?></b></td>
        <td><?= $user->profile->inn?></td>
    </tr>
    <tr>
        <td><b><?= Yii::t('app', 'ZKPO') ?></b></td>
        <td><?= $user->profile->zkpo ?></td>
    </tr>
    <tr>
        <td><b><?= Yii::t('app', 'Legal address') ?></b></td>
        <td><?= $user->profile->u_address ?></td>
    </tr>
    <tr>
        <td><b><?= Yii::t('app', 'Personal address') ?></b></td>
        <td><?= $user->profile->f_address?></td>
    </tr>
    <tr>
        <td><b><?= Yii::t('app', 'Member') ?></b></td>
        <td><?= $user->profile->member?></td>
    </tr>
    <tr>
        <td><b><?= Yii::t('app', 'Phone') ?></b></td>
        <td><?= $user->profile->phone?></td>
    </tr>
    <tr>
        <td><b><?= Yii::t('app', 'Fax') ?></b></td>
        <td><?= $user->profile->fax?></td>
    </tr>
    <tr>
        <td><b><?= Yii::t('app', 'E-mail') ?></b></td>
        <td><?= $user->profile->member_email?></td>
    </tr>
    <tr>
        <td><b><?= Yii::t('app', 'Site') ?></b></td>
        <td><?= $user->profile->site ?></td>
    </tr>
    <tr>
        <td><strong><?= Yii::t('app', 'Edrpou bank') ?>:</strong></td>
        <td><?= $user->profile->edrpou_bank; ?></td>
    </tr>
    <tr>
        <td><strong><?= Yii::t('app', 'Mfo') ?>:</strong></td>
        <td><?= $user->profile->mfo; ?></td>
    </tr>
    <tr>
        <td><strong><?= Yii::t('app', 'Bank name') ?>:</strong></td>
        <td><?= $user->profile->bank_name; ?></td>
    </tr>
    <tr>
        <td><strong><?= Yii::t('user', 'Registration time') ?>:</strong></td>
        <td><?= Yii::t('user', '{0, date, MMMM dd, YYYY HH:mm}', [$user->created_at]) ?></td>
    </tr>
    <?php if ($user->registration_ip !== null): ?>
        <tr>
            <td><strong><?= Yii::t('user', 'Registration IP') ?>:</strong></td>
            <td><?= $user->registration_ip ?></td>
        </tr>
    <?php endif ?>
    <tr>
        <td><strong><?= Yii::t('user', 'Confirmation status') ?>:</strong></td>
        <?php if ($user->isConfirmed): ?>
            <td class="text-success"><?= Yii::t('user', 'Confirmed at {0, date, MMMM dd, YYYY HH:mm}', [$user->confirmed_at]) ?></td>
        <?php else: ?>
            <td class="text-danger"><?= Yii::t('user', 'Unconfirmed') ?></td>
        <?php endif ?>
    </tr>
    <tr>
        <td><strong><?= Yii::t('user', 'Block status') ?>:</strong></td>
        <?php if ($user->isBlocked): ?>
            <td class="text-danger"><?= Yii::t('user', 'Blocked at {0, date, MMMM dd, YYYY HH:mm}', [$user->blocked_at]) ?></td>
        <?php else: ?>
            <td class="text-success"><?= Yii::t('user', 'Not blocked') ?></td>
        <?php endif ?>
        <?php if($user->userFiles):?>
            <div class="row" id='auction-documents'>
                <table class='table table-striped table-bordered'>
                <tr>
                    <td><?=Yii::t('app', 'User documents'); ?>:</td>
                </tr>
                    <?php foreach ($user->userFiles as $k => $file): ?>
                        <tr>
                            <td>
                                <?=Html::a($file->name, ['/files/download', 'id' => $file->id], ['id' => $file->type == 'no type' ?
                                    "document-id" : '', 'name' => $file->type]); ?>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        <?php endif; ?>

    </tr>
</table>

<?php $this->endContent() ?>
