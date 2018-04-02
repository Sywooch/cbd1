<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "help_video".
 *
 * @property integer $id
 * @property string $link
 * @property integer $cat_id
 * @property integer $created_at
 *
 * @property HelpCategory $cat
 */
class HelpVideo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'help_video';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['link', 'cat_id'], 'required'],
            [['cat_id', 'created_at'], 'integer'],
            [['link'], 'string', 'max' => 255],
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
            'link' => Yii::t('app', 'Link'),
            'cat_id' => Yii::t('app', 'Cat ID'),
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
