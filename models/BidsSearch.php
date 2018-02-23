<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\Bids;

/**
 * BidsSearch represents the model behind the search form about `api\Bids`.
 */
class BidsSearch extends Bids
{
    public $lotName;
    public $organizationName;
    public $statusName;
    public $_auctionID;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['unique_id', 'value_valueAddedTaxIncluded', 'updated_at', 'accepted'], 'integer'],
            [['id', 'status', 'value_currency', 'participationUrl','lotName', 'organizationName', 'statusName'], 'safe'],
            [['value_amount'], 'number'],
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
        $query = Bids::find();

        $query->joinWith(['lot', 'apiAuction']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
            ],
        ]);

        $dataProvider->sort->attributes['lotName'] = [
            'asc' => ['lots.name' => SORT_ASC],
            'desc' => ['lots.name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['statusName'] = [
            'asc' => ['api_auctions.status' => SORT_ASC],
            'desc' => ['api_auctions.status' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['organizationName'] = [
            'asc' => ['api_organizations.name' => SORT_ASC],
            'desc' => ['api_organizations.name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['auctionID'] = [
            'asc' => ['api_auctions.auctionID' => SORT_ASC],
            'desc' => ['api_auctions.auctionID' => SORT_DESC],
        ];

        $this->load($params);

        $query->joinWith(['organization']);

        $query->andFilterWhere(['like', 'api_auctions.auctionID', $this->_auctionID]);

        if(Yii::$app->user->can('member')){
            $query->andWhere(['api_bids.user_id'=>Yii::$app->user->id,]);
        }
        elseif (Yii::$app->user->can('org')){
            $query->andWhere(['lots.user_id'=>Yii::$app->user->id])
            ;
        }
        elseif(Yii::$app->user->can('admin')){
            $query->andWhere('api_auctions.id is not null');
        }

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'unique_id' => $this->unique_id,
            'value_amount' => $this->value_amount,
            'value_valueAddedTaxIncluded' => $this->value_valueAddedTaxIncluded,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'accepted' => $this->accepted,
            'api_auctions.status' => $this->statusName,
        ]);
        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'api_bids.status', $this->status])
            ->andFilterWhere(['like', 'value_currency', $this->value_currency])
            ->andFilterWhere(['like', 'lots.name', $this->lotName])
            ->andFilterWhere(['like', 'api_organizations.name', $this->organizationName])
        ;

        return $dataProvider;
    }

    public function getAuctionID(){
        return $this->_auctionID;
    }

    public function setAuctionID($value){
        $this->_auctionID = $value;
    }
}
