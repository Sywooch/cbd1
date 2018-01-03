<?php

namespace api;

use Yii;

/**
 * This is the model class for table "bid_parameters".
 *
 * @property integer $id
 * @property string $bid_id
 * @property integer $parameter_id
 */
class BidParameters extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'api_bid_parameters';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bid_id', 'parameter_id'], 'required'],
            [['parameter_id'], 'integer'],
            [['bid_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'bid_id' => Yii::t('app', 'Bid ID'),
            'parameter_id' => Yii::t('app', 'Parameter ID'),
        ];
    }
}
