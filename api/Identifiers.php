<?php

namespace api;

use api\base\ActiveRecord;
use Yii;

/**
 * This is the model class for table "identifiers".
 *
 * @property integer $id
 * @property string $scheme
 * @property string $legalName
 * @property string $uri
 */
class Identifiers extends ActiveRecord
{

    public function behaviors()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'api_identifiers';
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
                    'scheme',
                    'legalName',
//                    'uri',
                ],
                'required',
            ],
//          [['id'], 'integer'],
            [
                [
                    'uri',
                ],
                'string',
            ],
            [
                [
                    'id',
                    'scheme',
                    'legalName',
                ],
                'string',
                'max' => 255,
            ],
        ];
    }

    public function fields(){
        return [
            'id',
            'scheme',
            'legalName',
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'scheme' => Yii::t('app', 'Scheme'),
            'legalName' => Yii::t('app', 'Legal Name'),
            'uri' => Yii::t('app', 'Uri'),
        ];
    }

}
