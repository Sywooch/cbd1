<?php

//namespace yii\rest;
namespace app\controllers;

use Yii;
use yii\rest\Action;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\web\ServerErrorHttpException;

class UpdateAction extends Action
{
    /**
     * @var string the scenario to be assigned to the model before it is validated and updated.
     */
    public $scenario = Model::SCENARIO_DEFAULT;


    /**
     * Updates an existing model.
     * @param string $id the primary key of the model.
     * @return \yii\db\ActiveRecordInterface the model being updated
     * @throws ServerErrorHttpException if there is any error when updating the model
     */
    public function run($id)
    {
        /* @var $model ActiveRecord */
        $model = $this->findModel($id); //return $model->last_user; exit;

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        $model->scenario = $this->scenario;
        $arr = Yii::$app->getRequest()->getBodyParams(); //return $arr['last_user']; exit;

        if($model->type_id=="1") {

            $sql = Yii::$app->db->createCommand("SELECT update_price_function(:last_user_in, :id_in, :price_in)")
                ->bindValues([':last_user_in' => $arr['last_user'], ':id_in' => $model->id, ':price_in' => $arr['price_in']]);
            $res = $sql->queryOne();
//            var_dump($res); exit;
            foreach ($res as $key => $value) {
                return $value;

            }
        }
        if($model->type_id=="2") {


            if ($model->last_user > 0)
            {
                return "3";
            }
            $sql = Yii::$app->db->createCommand("SELECT update_price_tipe_id_2_function(:last_user_in, :id_in, :price_in)")
                ->bindValues([':last_user_in' => $arr['last_user'], ':id_in' => $model->id, ':price_in' => $arr['price_in']]);
            $res = $sql->queryOne();

            //var_dump($res);
            foreach ($res as $key => $value) {
                return $value;

            }
        }
        /*
        if($model->last_user != $arr['last_user'])
        {
            Yii::$app->db->createCommand("CALL update_price_procedure(:last_user,:id)")
                ->bindValues([':last_user' => $arr['last_user'], ':id' => $model->id,])
                ->execute();
            return false;
        }
        else
        {
            return false;
        }*/
        //return $model;
    }
}
