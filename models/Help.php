<?php

namespace app\models;

use Yii;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "help".
 *
 * @property integer $id
 * @property string $title
 * @property string $h1
 * @property string $short_text
 * @property string $text
 * @property string $description
 * @property string $key_words
 * @property integer $cat_id
 * @property string $slug
 * @property integer $created_at
 *
 * @property HelpCategory $cat
 */
class Help extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'help';
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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'h1', 'short_text', 'text', 'description', 'key_words', 'cat_id', 'slug'], 'required'],
            [['short_text', 'text'], 'string'],
            [['cat_id', 'created_at'], 'integer'],
            [['title', 'h1', 'description', 'key_words', 'slug'], 'string', 'max' => 255],
            [['cat_id'], 'exist', 'skipOnError' => true, 'targetClass' => HelpCategory::className(), 'targetAttribute' => ['cat_id' => 'id']],
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
            'h1' => Yii::t('app', 'H1'),
            'short_text' => Yii::t('app', 'Short Text'),
            'text' => Yii::t('app', 'Text'),
            'description' => Yii::t('app', 'Description'),
            'key_words' => Yii::t('app', 'Key Words'),
            'cat_id' => Yii::t('app', 'Cat ID'),
            'slug' => Yii::t('app', 'Slug'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCat()
    {
        return $this->hasOne(HelpCategory::className(), ['id' => 'cat_id']);
    }
}
