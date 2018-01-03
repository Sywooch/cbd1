<?php

namespace api;

use api\base\ActiveRecord;
use Yii;

/**
 * This is the model class for table "features".
 *
 * @property integer $id
 * @property string $code
 * @property string $featureOf
 * @property integer $relatedItem
 * @property string $title
 * @property string $description
 * @property double $enum_value
 * @property string $enum_title
 * @property string $enum_description
 */
class Features extends ActiveRecord
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
        return 'api_features';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'code',
                    'featureOf',
                    'relatedItem',
                    'title',
                    'enum_title',
                ],
                'required',
            ],
            [
                [
                    'relatedItem',
                ],
                'integer',
            ],
            [
                [
                    'description',
                    'enum_description',
                ],
                'string',
            ],
            [
                [
                    'enum_value',
                ],
                'number',
            ],
            [
                [
                    'code',
                    'title',
                    'enum_title',
                ],
                'string',
                'max' => 255,
            ],
            [
                [
                    'featureOf',
                ],
                'string',
                'max' => 15,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'featureOf' => 'Feature Of',
            'relatedItem' => 'Related Item',
            'title' => 'Title',
            'description' => 'Description',
            'enum_value' => 'Enum Value',
            'enum_title' => 'Enum Title',
            'enum_description' => 'Enum Description',
        ];
    }

}
