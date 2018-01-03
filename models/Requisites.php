<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "requisites".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $account
 * @property string $bank
 * @property string $city
 * @property integer $mfo
 * @property integer $zkpo
 * @property string $title;
 */
class Requisites extends \yii\db\ActiveRecord
{
    public $title;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'requisites';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'account', 'bank', 'city', 'mfo', 'zkpo'], 'required'],
            [['user_id', 'account', 'mfo', 'zkpo'], 'integer'],
            [['bank'], 'string', 'max' => 50],
            [['city'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'account' => Yii::t('app', 'Рахунок'),
            'bank' => Yii::t('app', 'Банк'),
            'city' => Yii::t('app', 'в місті'),
            'mfo' => Yii::t('app', 'МФО'),
            'zkpo' => Yii::t('app', 'ЄДРПОУ'),
        ];
    }
}
