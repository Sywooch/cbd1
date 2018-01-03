<?php

namespace api;

use Yii;

/**
 * This is the model class for table "organizations_identifiers".
 *
 * @property integer $id
 * @property integer $organization_id
 * @property integer $identifier_id
 */
class OrganizationsIdentifiers extends \yii\db\ActiveRecord
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
        return 'api_organizations_identifiers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['organization_id', 'identifier_id'], 'required'],
            [['organization_id', 'identifier_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'organization_id' => Yii::t('app', 'Organization ID'),
            'identifier_id' => Yii::t('app', 'Identifier ID'),
        ];
    }
}
