<?php

namespace api;

use api\base\ActiveRecord;
use Yii;

/**
 * This is the model class for table "classifications".
 *
 * @property string $id
 * @property string $scheme
 * @property string $description
 * @property integer $uri
 */
class Classifications extends ActiveRecord
{

    public function behaviors()
    {
        return [];
    }

    public function fields(){
        return [
            'id',
            'scheme' => function($model){
                return 'CAV';
            },
            'description',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'api_classifications';
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
                    // 'description',
                ],
                'required',
            ],
            [
                [
                    'description',
                ],
                'string',
            ],
            [
                [
                    'uri',
                ],
                'integer',
            ],
            [
                [
                    'id',
                    'scheme',
                ],
                'string',
                'max' => 255,
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
            'scheme' => Yii::t('app', 'Scheme'),
            'description' => Yii::t('app', 'Description'),
            'uri' => Yii::t('app', 'Uri'),
        ];
    }

    public function getFullName(){
        return $this->id . ' - ' . $this->description;
    }
}
