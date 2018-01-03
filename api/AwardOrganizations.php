<?php

namespace api;

use Yii;

/**
 * This is the model class for table "award_organizations".
 *
 * @property integer $id
 * @property string $award_id
 * @property integer $organization_id
 */
class AwardOrganizations extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'api_award_organizations';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['award_id', 'organization_id'], 'required'],
            [['award_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'award_id' => Yii::t('app', 'Award ID'),
            'organization_id' => Yii::t('app', 'Organization ID'),
        ];
    }

}
