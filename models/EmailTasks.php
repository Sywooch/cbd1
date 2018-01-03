<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "email_tasks".
 *
 * @property integer $id
 * @property string $email
 * @property string $message
 * @property integer $process
 */
class EmailTasks extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'email_tasks';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'message'], 'required'],
            [['message'], 'string'],
            [['process'], 'integer'],
            [['email'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'email' => Yii::t('app', 'Email'),
            'message' => Yii::t('app', 'Message'),
            'process' => Yii::t('app', 'Process'),
        ];
    }
}
