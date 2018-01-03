<?php

namespace app\models;

use Yii;
use app\models\User;

/**
 * This is the model class for table "messages".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $notes
 * @property string $date
 */
class Messages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'messages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'notes'], 'required'],
            [['user_id'], 'integer'],
            [['notes'], 'string', 'max' => 500]
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
            'notes' => Yii::t('app', 'Notes ID'),
        ];
    }
    public function sendMessage($user_id,$notes,$email = false)
    {
        $user = User::findOne(['id' =>$user_id]);
        if(!$user){
            return false;
        }
        $model = new Messages();
        $model->setAttributes(['user_id' => $user_id, 'notes' => $notes,]);
        if($email == true)
        {
            $task = new EmailTasks([
                'email' => $user->email,
                'message' => $notes,
            ]);

            $task->save(false);
        }

        return $model->save(false);
    }
}
