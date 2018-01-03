<?php

namespace api;

use Yii;

/**
 * This is the model class for table "items_classifications".
 *
 * @property integer $id
 * @property integer $classification_id
 * @property integer $item_id
 */
class ItemsClassifications extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'api_items_classifications';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'classification_id', 'item_id',
                ],
                'required',
            ],
            [
                [
                    'item_id',
                ],
                'integer',
            ],
            // [
            //     [
            //         'classification_id',
            //     ],
            //     'string', 'max' => 25,
            // ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'classification_id' => Yii::t('app', 'Classification ID'),
            'item_id' => Yii::t('app', 'Item ID'),
        ];
    }

    public function getClassification(){
        return $this->hasOne(Classifications::className(), ['id' => 'classification_id']);
    }

    public function getName(){
        return $this->classification->description . ' (' . $this->classification_id . ')';
    }
}
