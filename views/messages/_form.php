<?php
/**
 * Created by PhpStorm.
 * User: NeiroN
 * Date: 02.10.2015
 * Time: 0:21
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\Lots */
/* @var $model app\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row">
    <div class="col-md-3">
        <?= $this->render('../cabinet/_menu') ?>
    </div>
    <div class="col-md-9">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="lots-form">