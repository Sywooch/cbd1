<?php

namespace api;

use api\base\ActiveRecord;
use app\models\Lots;
use Yii;

/**
 * This is the model class for table "items".
 *
 * @property integer $id
 * @property integer $auction_id
 * @property string $description
 * @property string $classification_id
 * @property string $unit_code
 * @property string $unit_name
 * @property integer $quantity
 * @property string $address_postalCode
 * @property string $address_countryName
 * @property string $address_streetAddress
 * @property string $address_region
 * @property string $address_locality
 * @property double $location_latitude
 * @property double $location_longitude
 * @property string $relatedLot
 * @property integer $created_at
 * @property integer $updated_at
 */
class Items extends ActiveRecord
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
        return 'api_items';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
//                    'auction_id',
                    'description',
                    'classification_id',
                    'unit_code',
                    'unit_name',
                    'quantity',
                    'address_countryName',
                    'address_locality',
                    'address_region',
                    'address_postalCode',
                    'address_streetAddress',
                    // 'location_latitude',
                    // 'location_longitude',
                ],
                'required',
            ],
            [
                [
                    'auction_id',
                    'api_auction_id',
                    'quantity',
                    'created_at',
                    'updated_at',
                ],
                'integer',
            ],
            [
                [
                    'description',
                ],
                'string',
            ],
            [
                [
//                    'classification_id',
                    'unit_code',
                    'address_postalCode',
                ],
                'string',
                'max' => 25],
            [
                [
                    'unit_name',
                    'address_countryName',
                    'address_streetAddress',
                    'address_region',
                    'address_locality',
                    'relatedLot',
                ],
                'string',
            ],
            [
                [
                    'location_latitude',
                    'location_longitude',
                ],
                'validateLatLng'
            ],
            [
                [
                    'classification',
                    'id',
                ],
                'safe',
            ],
            [['quantity'], 'default', 'value' => '1'],
            [['classification_id'], 'validateClassification'],
        ];
    }

    public function validateClassification($attr){

        $this->classification_id = explode(' - ', $this->classification_id)[0];
        if(!Classifications::findOne(['id' => $this->$attr])){
            $this->addError($attr, Yii::t('app', 'Classification doesnt exist: {classification}'), ['classification' => $attr]);
        }
    }

    public function validateLatLng($attribute, $params = []){
        $this->$attribute = str_replace(',', '.', $this->$attribute);
        return true;
    }

    public function fields()
    {
        return [
            // 'id',
            'description',
            'classification',
//            'address',
            'unit' => function($model){
                return [
                    'name' => $model->unit_name,
                    'code' => $model->unit_code,
                ];
            },
            'quantity',
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'auction_id' => Yii::t('app', 'auction ID'),
            'description' => Yii::t('app', 'Description'),
            'classification_id' => Yii::t('app', 'Classification ID'),
            'unit_code' => Yii::t('app', 'Unit Code'),
            'unit_name' => Yii::t('app', 'Unit Name'),
            'quantity' => Yii::t('app', 'Quantity'),
            'address_postalCode' => Yii::t('app', 'Address Postal Code'),
            'address_countryName' => Yii::t('app', 'Address Country Name'),
            'address_streetAddress' => Yii::t('app', 'Address Street Address'),
            'address_region' => Yii::t('app', 'Address Region'),
            'address_locality' => Yii::t('app', 'Address Locality'),
            'location_latitude' => Yii::t('app', 'Location Latitude'),
            'location_longitude' => Yii::t('app', 'Location Longitude'),
            'relatedLot' => Yii::t('app', 'Related Lot'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'classification_scheme' => Yii::t('app', 'Classification Scheme'),
            'locality' => Yii::t('app', 'Locality Name'),
            'region' => Yii::t('app', 'Region Name'),
            'postalCode' => Yii::t('app', 'Postal Code'),
            'streetAddress' => Yii::t('app', 'Address'),
            'classification_description' => Yii::t('app', 'Classification Description'),
        ];
    }

    public function getClassification(){
        return $this->hasOne(Classifications::className(), ['id' => 'classification_id']);
    }

    public function setClassification($value){
        if(false == ($classification = Classifications::findOne(['id' => $value['id'], 'scheme' => $value['scheme']]))){
            $classification = new Classifications([
                'scheme'        => $value['scheme'],
                'id'            => $value['id'],
                'description'   => $value['description'],
            ]);
            $classification->save();
        }
        $this->classification_id = $classification->id;
    }

    public function getLot(){
        return $this->hasOne(Lots::className(), ['id' => 'auction_id']);
    }

    public function getAuction(){
        return $this->hasOne(Auctions::className(), ['unique_id' => 'api_auction_id']);
    }

    public function beforeValidate()
    {
        $units = $this->units();
        $this->unit_name = isset($units[$this->unit_code]) ? $units[$this->unit_code] : null;
        return parent::beforeValidate();
    }

    public function units(){
        return [
            'BX'    => 'ящик',
            'D03'   => 'кіловат/година',
            'D64'   => 'блок',
            'E48'   => 'послуга',
            'E54'   => 'рейс',
            'H87'   => 'штуки',
            'HAR'   => 'гектар',
            'KGM'   => 'кілограми',
            'KMT'   => 'кілометри',
            'LO'    => 'лот',
            'LTR'   => 'літр',
            'MON'   => 'місяць',
            'MTK'   => 'метри квадратні',
            'MTQ'   => 'метри кубічні',
            'MTR'   => 'метри',
            'NMP'   => 'пучок',
            'PK'    => 'упаковки',
            'PR'    => 'пара',
            'SET'   => 'набір',
            'TNE'   => 'тони',
        ];
    }

    public function getUnit_name(){
        return isset($this->units()[$this->unit_code]) ? $this->units()[$this->unit_code] : Yii::t('app', '(not set)');
    }

}
