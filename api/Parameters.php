<?php

namespace api;

use Yii;

/**
 * This is the model class for table "parameters".
 *
 * @property integer $id
 * @property string $code
 * @property double $value
 */
class Parameters extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'api_parameters';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'code', 'value'], 'required'],
            [['id'], 'integer'],
            [['value'], 'number'],
            [['code'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'code' => Yii::t('app', 'Code'),
            'value' => Yii::t('app', 'Value'),
        ];
    }
}
