<?php

namespace api;

use api\base\ActiveRecord;
use Yii;

/**
 * This is the model class for table "lots".
 *
 * @property string $id
 * @property string $title
 * @property string $description
 * @property double $value_amount
 * @property string $value_currency
 * @property integer $value_valueAddedTaxIncluded
 * @property double $guarantee_amount
 * @property string $guarantee_currency
 * @property string $date
 * @property double $minimalStep_amount
 * @property string $minimalStep_currency
 * @property integer $minimalStep_valueAddedTaxIncluded
 * @property string $auctionPeriod_startDate
 * @property string $auctionPeriod_endDate
 * @property string $auctionUrl
 * @property string $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Lots extends ActiveRecord
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
        return 'api_lots';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'id',
                    'title',
                    'value_amount',
                    'value_valueAddedTaxIncluded',
                    'guarantee_amount',
                    'guarantee_currency',
                    'date',
                    'minimalStep_amount',
                    'minimalStep_currency',
                    'minimalStep_valueAddedTaxIncluded',
                    'auctionPeriod_startDate',
                    'auctionPeriod_endDate',
                    'auctionUrl',
                    'status',
                ],
                'required',
            ],
            [
                [
                    'description',
                    'auctionUrl',
                ],
                'string',
            ],
            [
                [
                    'value_amount',
                    'guarantee_amount',
                    'minimalStep_amount',
                ],
                'number',
            ],
            [
                [
                    'value_valueAddedTaxIncluded',
                    'minimalStep_valueAddedTaxIncluded',
                    'created_at',
                    'updated_at',
                ],
                'integer',
            ],
            [
                [
                    'id',
                    'title',
                ],
                'string',
                'max' => 255,
            ],
            [
                [
                    'value_currency',
                    'guarantee_currency',
                    'minimalStep_currency',
                ],
                'string',
                'max' => 3,
            ],
            [
                [
                    'date',
                    'auctionPeriod_startDate',
                    'auctionPeriod_endDate',
                ],
                'string',
                'max' => 35,
            ],
            [
                [
                    'status',
                ],
                'string',
                'max' => 25,
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
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'value_amount' => Yii::t('app', 'Value Amount'),
            'value_currency' => Yii::t('app', 'Value Currency'),
            'value_valueAddedTaxIncluded' => Yii::t('app', 'Value Value Added Tax Included'),
            'guarantee_amount' => Yii::t('app', 'Guarantee Amount'),
            'guarantee_currency' => Yii::t('app', 'Guarantee Currency'),
            'date' => Yii::t('app', 'Date'),
            'minimalStep_amount' => Yii::t('app', 'Minimal Step Amount'),
            'minimalStep_currency' => Yii::t('app', 'Minimal Step Currency'),
            'minimalStep_valueAddedTaxIncluded' => Yii::t('app', 'Minimal Step Value Added Tax Included'),
            'auctionPeriod_startDate' => Yii::t('app', 'Auction Period Start Date'),
            'auctionPeriod_endDate' => Yii::t('app', 'Auction Period End Date'),
            'auctionUrl' => Yii::t('app', 'Auction Url'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function getValues(){
        return $this->hasMany(LotValues::className(), ['relatedLot' => 'id']);
    }

}
