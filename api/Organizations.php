<?php

namespace api;

use dektrium\user\models\Profile;
use Yii;
use app\models\User;
use api\base\ActiveRecord;

/**
 * This is the model class for table "organizations".
 *
 * @property integer $id
 * @property string $name
 * @property integer $address_postalCode
 * @property string $address_countryName
 * @property string $address_streetAddress
 * @property string $address_region
 * @property string $address_locality
 * @property string $contactPoint_name
 * @property string $contactPoint_email
 * @property string $contactPoint_telephone
 * @property string $contactPoint_faxNumber
 * @property integer $identifier_id
 * @property integer $created_at
 * @property integer $updated_at
 */

class Organizations extends ActiveRecord
{

    private $_identifier;


    public function behaviors()
    {
        return parent::behaviors();
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'api_organizations';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'name',
                    'address_postalCode',
                    'address_countryName',
                    'address_streetAddress',
                    'address_region',
                    'address_locality',
                    'contactPoint_name',
                    'contactPoint_telephone',
                    // 'identifier',
                ],
                'required',
            ],
            [
                [
                    'created_at',
                    'updated_at',
                    'user_id',
                ],
                'integer',
            ],
            [
                [
                    'id',
                    'name',
                    'address_countryName',
                    'address_postalCode',
                    'address_streetAddress',
                    'address_region',
                    'address_locality',
                    'contactPoint_name',
                    'contactPoint_email',
                    'contactPoint_telephone',
                    'contactPoint_faxNumber',
                    'identifier_id',
                ],
                'string', 'max' => 255
            ],
            [
                [
                    'kind',
                ],
                'string',
                'max' => 25,
            ],
            [['identifier'], 'safe'],
        ];
    }

    public function fields()
    {
        return [
            'contactPoint' => function($model){
                return [
                    'name' => $model->contactPoint_name,
                    'email' => $model->contactPoint_email,
                    'telephone' => $model->contactPoint_telephone,
                    'faxNumber' => $model->contactPoint_faxNumber,
                ];
            },
            'identifier',
            'name' => function($model){
                return $model->name ? $model->name : $model->contactPoint_name;
            },
//            'kind',
            'address' => function($model){
                return [
                    'streetAddress' => $model->address_streetAddress,
                    'countryName' => $model->address_countryName,
                    'region' => $model->address_region,
                    'locality' => $model->address_locality,
                ];
            },
            'additionalIdentifiers' => function($model){
                if($model->user){
                    return [
                        [
                            'id' => $model->user->profile->licenseNumber ?: '1111111',
//                            'description' => 'Номер фінансової ліцензії',
                            'scheme' => 'UA-FIN'
                        ]
                    ];
                }
                return [];
            },
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'address_postalCode' => 'Address Postal Code',
            'address_countryName' => 'Address Country Name',
            'address_streetAddress' => 'Address Street Address',
            'address_region' => 'Address Region',
            'address_locality' => 'Address Locality',
            'contactPoint_name' => 'Contact Point Name',
            'contactPoint_email' => 'Contact Point Email',
            'contactPoint_telephone' => 'Contact Point Telephone',
            'contactPoint_faxNumber' => 'Contact Point Fax Number',
            'identifier_id' => 'Identifier ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function setIdentifier($value){
        $this->_identifier = $value;
    }

    public function getIdentifier(){
        return $this->_identifier ?: $this->hasOne(Identifiers::className(), ['id' => 'identifier_id']);
    }

    public function getUser(){
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    public function getProfile(){
        return $this->hasOne(Profile::className(), ['user_id' => 'user_id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if(false == ($identifier = Identifiers::findOne(['id' => $this->_identifier['id']]))){
            $identifier = new Identifiers([
                'id' => $this->_identifier['id'] ?: null,
                'scheme' => $this->_identifier['scheme'],
                'legalName' => $this->name,
                'uri' => isset($this->_identifier['uri']) ? $this->_identifier['uri'] : null,
            ]);
            try{
                if(!$identifier->save(false) && YII_DEBUG){
                    echo "Identifier save error:\n";
                    print_r($identifier->errors);
                }
                else{
                    $this->updateAttributes(['identifier_id' => $identifier->id]);
                }
            } catch(\Exception $e){
                
            }
        }
        else{
            $this->updateAttributes(['identifier_id' => $identifier->id]);
        }
    }

}
