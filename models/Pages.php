<?php

namespace app\models;

use Yii;
use yii\behaviors\SluggableBehavior;

/**
*/
class Pages extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'pages';
    }

    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'slugAttribute' => 'slug',
            ]
        ];
    }

    public function rules()
    {
        return [
            [['title', 'text', 'description', 'key_word','h1'], 'required'],
            [['text','slug'], 'string'],
            [['title','h1'], 'string', 'max' => 100],
            [['description', 'key_word'], 'string', 'max' => 255],
            [['title','slug'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'text' => Yii::t('app', 'Text'),
            'description' => Yii::t('app', 'Description'),
            'key_word' => Yii::t('app', 'Key Word'),
            'h1' => Yii::t('app', 'H1'),
			'slug' => Yii::t('app', 'Slug'),
        ];
    }

}
