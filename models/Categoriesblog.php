<?php

namespace app\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use app\models\Posts;
use v0lume\yii2\metaTags\MetaTagBehavior;

/**
 */
class Categoriesblog extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'categoriesblog';
    }

    //Поведение ( реализация слагов)
    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'slugAttribute' => 'slug',
            ],
        ];
    }

    public function rules()
    {
        return [
            [['title', 'description', 'key_words'], 'required'],
            [['title','title_ru'], 'string', 'max' => 100],
            [['description', 'key_words','description_ru', 'key_words_ru','slug'], 'string', 'max' => 255],
            [['title','title_ru'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'key_words' => Yii::t('app', 'Key Words'),
        ];
    }


}
