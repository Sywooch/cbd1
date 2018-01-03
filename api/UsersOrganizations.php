<?php

namespace api;

use Yii;

/**
 * This is the model class for table "users_organizations".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $organization_id
 */
class UsersOrganizations extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'api_users_organizations';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'organization_id'], 'required'],
            [['user_id', 'organization_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'organization_id' => Yii::t('app', 'Organization ID'),
        ];
    }
}
