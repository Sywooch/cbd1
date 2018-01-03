<?php

namespace api;

use api\base\ActiveRecord;
use Yii;

/**
 * This is the model class for table "lot_values".
 *
 * @property integer $id
 * @property double $value_amount
 * @property string $value_currency
 * @property integer $value_valueAddedTaxIncluded
 * @property string $relatedLot
 * @property string $date
 * @property string $participationUrl
 * @property integer $created_at
 * @property integer $updated_at
 */
class LotValues extends ActiveRecord
{

    public function behaviors()
    {
        return parent::behaviors();
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'api_lot_values';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'value_amount',
                    'value_currency',
                    'value_valueAddedTaxIncluded',
                    'relatedLot',
                    'date',
                    'participationUrl',
                ],
                'required'
            ],
            [
                [
                    'value_amount',
                ],
                'number'
            ],
            [
                [
                    'value_currency',
                ],
                'string'
            ],
            [
                [
                    'value_valueAddedTaxIncluded',
                    'created_at',
                    'updated_at',
                ],
                'integer'
            ],
            [
                [
                    'relatedLot',
                    'participationUrl',
                ],
                'string',
                'max' => 255
            ],
            [
                [
                    'date',
                ],
                'string',
                'max' => 35
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'value_amount' => Yii::t('app', 'Value Amount'),
            'value_currency' => Yii::t('app', 'Value Currency'),
            'value_valueAddedTaxIncluded' => Yii::t('app', 'Value Value Added Tax Included'),
            'relatedLot' => Yii::t('app', 'Related Lot'),
            'date' => Yii::t('app', 'Date'),
            'participationUrl' => Yii::t('app', 'Participation Url'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

}
