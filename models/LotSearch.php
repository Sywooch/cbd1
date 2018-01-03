<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Lots;

/**
 * LotSearch represents the model behind the search form about `app\models\Lots`.
 */
class LotSearch extends Lots
{

    public $auctionID;
    public $statusName;
    public $published;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'requisites_id', 'status','num'], 'integer'],
            [[
                'name',
                'description',
                'address',
                'delivery_time',
                'delivery_term',
                'requires',
                'payment_term',
                'payment_order',
                'member_require',
                'notes',
                'date',
                // 'auction_date',
                'auctionID',
                'statusName',
                'published',
            ], 'safe'],
            [['start_price', 'step'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Lots::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC],
            ]
        ]);

        $dataProvider->sort->attributes['auctionID'] = [
            'asc' => ['auctionID' => SORT_ASC],
            'desc' => ['auctionID' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['statusName'] = [
            'asc' => ['api_auctions.status' => SORT_ASC],
            'desc' => ['api_auctions.status' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['published'] = [
            'asc' => ['lot_lock' => SORT_ASC],
            'desc' => ['lot_lock' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->joinWith(['apiAuction']);

        $query->andFilterWhere([
            'delivery_time' => $this->delivery_time,
            'requisites_id' => $this->requisites_id,
            'dogovor_id' => $this->dogovor_id,
            'auction_date' => $this->auction_date,
        ]);

        if(Yii::$app->user->can('member')){
            $query->andWhere(['lot_lock' => '1']);
            $query->andWhere(['api_auctions.status' => [
                'active.tendering',
                'active.auction',
                'active.qualification',
            ]]);
        }
        if(Yii::$app->user->can('org')){
            $query->andFilterWhere(['lots.lot_lock' => $this->published]);
            $query->andWhere(['user_id' => Yii::$app->user->id]);
        }
        $query->andWhere(['!=', 'user_id', 0]);

        if($this->statusName != 'draft'){
            $query->andFilterWhere(['like', 'api_auctions.status', $this->statusName]);
        }else{
            $query->andWhere(['api_auctions.unique_id' => null]);
        }

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'delivery_term', $this->delivery_term])
            ->andFilterWhere(['like', 'requires', $this->requires])
            ->andFilterWhere(['like', 'payment_term', $this->payment_term])
            ->andFilterWhere(['like', 'payment_order', $this->payment_order])
            ->andFilterWhere(['like', 'member_require', $this->member_require])
            ->andFilterWhere(['like', 'notes', $this->notes])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'auctionID', $this->auctionID])
        ;

        return $dataProvider;
    }
}
