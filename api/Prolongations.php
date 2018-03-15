<?php

namespace api;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "api_prolongations".
 *
 * @property integer $unique_id
 * @property string $id
 * @property string $contractID
 * @property string $dateCreated
 * @property string $status
 * @property string $description
 * @property string $datePublished
 * @property string $decisionID
 * @property string $reason
 */
class Prolongations extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'api_prolongations';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'contractID', 'status', 'decisionID'], 'required'],
            [['description', 'reason'], 'string'],
            [['id', 'contractID', 'decisionID'], 'string', 'max' => 255],
            [['dateCreated', 'status', 'datePublished'], 'string', 'max' => 35],
            [['id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'unique_id' => Yii::t('app', 'Unique ID'),
            'id' => Yii::t('app', 'ID'),
            'contractID' => Yii::t('app', 'Contract ID'),
            'dateCreated' => Yii::t('app', 'Date Created'),
            'status' => Yii::t('app', 'Status'),
            'description' => Yii::t('app', 'Description'),
            'datePublished' => Yii::t('app', 'Date Published'),
            'decisionID' => Yii::t('app', 'Decision ID'),
            'reason' => Yii::t('app', 'Reason'),
        ];
    }
}
