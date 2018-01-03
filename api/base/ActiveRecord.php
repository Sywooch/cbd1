<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace api\base;


use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord as BaseActiveRecord;


class ActiveRecord extends BaseActiveRecord
{

    public function behaviors(){
        return array_merge(parent::behaviors(), [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
            ],
            'timestamp_updated' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => 'updated_at',
                ],
            ],
        ]);
    }

    public function load($data, $formName = null){
        $data = isset($data['data']) ? $data['data'] : $data;
        $attributes = $this->attributes;

        $loaded = parent::load($data, $formName);

        foreach($attributes as $index => $value){
            if(strpos($index, '_')){
                $attr = explode('_', $index);
                if(isset($data[$attr[0]][$attr[1]])){
                    $this->$index = $data[$attr[0]][$attr[1]];
                    $loaded &= true;
                }
            }
        }
        return $loaded;
    }

}
