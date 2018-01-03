<?php

namespace app\models;

use Yii;
use app\helpers\Date;
use api\Auctions as ApiAuction;
use api\Cancellations;
/**
 * This is the model class for table "auctions".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $lot_id
 * @property integer $date_start
 * @property integer $date_stop
 * @property integer $last_price
 * @property integer $last_user
 * @property integer $last_date
 * @var $user User
 * @var $lots Lots
 */
class Auctions extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'auctions';
    }

    public function rules()
    {
        return [
            [['user_id', 'lot_id', 'date_start', 'date_stop', 'last_price', 'last_user', 'last_date'], 'required'],
            [['user_id', 'lot_id', 'last_user','type','lot_num','status','type_id','temp_step_down','step_down'], 'integer'],
            [['userName','lotName','date_start', 'date_stop', 'last_price', 'last_date','name','bidding_date'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name'  => Yii::t('app', 'AukName ID'),
            'user_id' => Yii::t('app', 'организатор'),
            'lot_id' => Yii::t('app', 'lot_id'),
            'lot_num'   =>  Yii::t('app', 'Lot ID'),
            'date_start' => Yii::t('app', 'AucStart ID'),
            'date_stop' => Yii::t('app', 'DateStop ID'),
            'bidding_date' => Yii::t('app', 'endBidding ID'),
            'last_price' => Yii::t('app', 'текущая цена'),
            'last_user' => Yii::t('app', 'ставка последнего юзера'),
            'last_date' => Yii::t('app', 'время последней ставки'),
            'lotName'   =>  Yii::t('app', 'Lot ID'),
            'status'    =>  Yii::t('app', 'Status ID'),
            'userName'    =>  Yii::t('app', 'OrgName ID'),
            'type'  =>  Yii::t('app', 'AucType ID'),
            'type_id'   =>  Yii::t('app', 'AucType ID'),
        ];
    }

    private function LotValidate($lot_id)
    {
        return false != Auctions::findOne(['lot_id' => $lot_id]);
    }

    public function getLot()
    {
        return $this->hasOne(Lots::className(), ['id' => 'lot_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    public function getTrade()
    {
        return $this->hasMany(Trade::className(), ['auk_id' => 'id']);
    }

    public function setUserName()
    {
        // add
    }

    public function getLotName()
    {
        return $this->lot ? $this->lot->name : '';
    }

    public function getUserName()
    {
        return $this->user ? $this->user->at_org : '';
    }

    public function setLotName()
    {
        // add
    }

    public function setFlags($value)
    {
        Yii::$app->session->set('user.flags',$value);
    }

    private function SendTime($last_date,$date_stop,$date_start)
    {
        if ((strtotime($date_stop) - strtotime($last_date)) < 601) {
            $step = 600;
            $diff = strtotime(date("Y-m-d H:i:s")) - strtotime($last_date);
            //$past_time = sprintf('%02d:%02d:%02d', $diff / 3600, ($diff % 3600) / 60, $diff % 60);
            $diff_time = $step - $diff; // 1 Hours perenesti v adminky // 7200 for
            return $diff_time;
        } else {
            $step = strtotime($date_stop) - strtotime($date_start);
            $diff = strtotime(date("Y-m-d H:i:s")) - strtotime($date_start);
            //$past_time = sprintf('%02d:%02d:%02d', $diff / 3600, ($diff % 3600) / 60, $diff % 60);
            $diff_time = $step - $diff; // 1 Hours perenesti v adminky // 7200 for
            return $diff_time;
        }
    }

    private function SendPerc($last_date,$date_stop,$date_start)
    {
        $diff = $this->SendTime($last_date,$date_stop,$date_start);
        if((strtotime($date_stop) - strtotime($last_date)) < 601)
        {
            $step = 600;
        }
        else
        {
            $step = strtotime($date_stop) - strtotime($date_start);
        }

        $perc = 100 * $diff / $step; //100-100*у/х 100*х/у-100
        return round($perc,2);
    }

    private function CheckRole()
    {
        if(Yii::$app->user->can('admin'))
        {
            return true;
        }
        if(Yii::$app->user->can('watcher'))
        {
            return false;
        }
        else {
            $res = Bidding::find()
                ->select('status')
                ->where(['auction_id' => $this->id])
                ->andWhere(['user_id' => Yii::$app->user->id])
                ->limit(1)
                ->one();

            if ($res == false) {
                return false;

            }  elseif ($res['status'] == "2") // 2=reject
            {
                return false;

            } elseif ($res['status'] == "0") // 0=default
            {
                return false;

            } elseif ($res['status'] == "1") // 0=accept
            {
                return true;
            }
        }
    }

    // for rest api
    public function fields()
    {
        return [
            'id',
            'user_id',
            'user_role' => function() {
                return $this->CheckRole();
            },
            'lot_id',
            'type_id'   =>  function () {
                return $this->type_id;
            },
            'member_user_id' => function () {
                return Yii::$app->user->identity->id;
            },
            'date_start'    => function($model)
            {
                return Yii::$app->formatter->asTime($model->date_start, "php:d.m.Y H:i:s");
            },
            'date_stop'    => function($model)
            {
                return Yii::$app->formatter->asTime($model->date_stop, "php:d.m.Y H:i:s");
            },
            'start_price' => function()
            {
                return $this->lot->start_price;
            },
            'step' => function()
            {
                return $this->lot->step;
            },
            'step_money' => function()
            {
                $step_money = $this->lot->start_price / 100 * $this->lot->step;
                return round($step_money,2);
            },
            'delta_money'   =>  function()
            {
                if($this->type_id==1) {
                    $delta_money = $this->last_price - $this->lot->start_price <= 0 ? 0 : $this->last_price - $this->lot->start_price;
                    return round($delta_money, 2);
                }
                if($this->type_id==2) {
                    $delta_money = $this->lot->start_price - $this->last_price <= 0 ? 0 : $this->lot->start_price - $this->last_price;
                    return round($delta_money, 2);
                }

            },
            'delta_perc' =>  function()
            {
                if($this->type_id==1) {
                    $delta_perc = ($this->last_price - $this->lot->start_price) / ($this->lot->start_price / 100) <= 0 ? 0 : ($this->last_price - $this->lot->start_price) / ($this->lot->start_price / 100);
                    return round($delta_perc, 2);
                }
                if($this->type_id==2) {
                    $delta_perc = ($this->lot->start_price - $this->last_price) / ($this->lot->start_price / 100) <= 0 ? 0 : ($this->lot->start_price - $this->last_price) / ($this->lot->start_price / 100);
                    return round($delta_perc, 2);
                }

            },
            'last_user',
            'next_price'    => function()
            {
                //$next_price = $this->last_price + ($this->lot->start_price / 100 * $this->lot->step);
                //return round($next_price, 2);

                if($this->type_id==1) {
                    if($this->last_price=="0")
                    {
                        $next_price = $this->lot->start_price + ($this->lot->start_price / 100 * $this->lot->step); return round($next_price, 2); }
                    //  return round($this->lot->start_price, 2);}
                    else
                        $next_price = $this->last_price + ($this->lot->start_price / 100 * $this->lot->step);
                    return round($next_price, 2);
                }
                if($this->type_id==2) {
                    if ($this->last_price == "0") {
                        return round($this->lot->start_price, 2);
                    } elseif ($this->last_user == "0")
                    {
                        $next_price = $this->last_price; //- ($this->lot->start_price / 100 * $this->lot->step);
                        return round($next_price, 2);
                    }
                    else
                    {
                        $next_price = $this->last_price + ($this->lot->start_price / 100 * $this->lot->step);
                        return round($next_price, 2);
                    }
                }

            },
            'time' => function()
            {
                $diff = $this->SendTime($this->last_date, $this->date_stop, $this->date_start); // $this->date_start for
                if($diff > 0)
                {
                    return $diff;
                }
                else
                {
                    return 0;
                }
            },
            //'diff'  =>  function()
            //{
            //    return strtotime($this->date_stop) - strtotime($this->last_date);
            //},
            'perc' => function()
            {
                $diff = $this->SendPerc($this->last_date,$this->date_stop, $this->date_start);
                if($diff > 0)
                {
                    return $diff;
                }
                else
                {
                    return 0;
                }
            },
            'last_price',
            'status',

        ];
    }

    public function extraFields()
    {
        return [
            //'date_start',
            //'date_stop',
        ];
    }

}
