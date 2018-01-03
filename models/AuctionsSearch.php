<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\Auctions;

/**
 * AuctionsSearch represents the model behind the search form about `app\models\Auctions`.
 */
class AuctionsSearch extends Auctions
{

    public $main_search;
    public $status;
    public $region;
    public $cav;
    public $org_name;
    public $type;

    public function rules()
    {
        $rules =  [];

        $rules[] = [['main_search', 'region', 'org_name'], 'string', 'max' => 255];
        $rules[] = [['status'], 'in', 'range' => array_keys((new Auctions())->statusNames)];
        $rules[] = [['cav'], 'safe'];
        $rules[] = [['type'], 'in', 'range' => array_keys(Lots::$procurementMethodTypes)];
        return $rules;
    }

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
        $query = Auctions::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query->groupBy('api_auctions.id'),
            'pagination' => [
                'defaultPageSize' => 4,
                'pageSize' => 4,
            ],
            'sort' => [
                'defaultOrder' => [
                    'unique_id' => SORT_DESC
                ]
            ]
        ]);


        $this->load($params);
        if (!$this->validate()) {
            DMF($this->errors);
            return $dataProvider;
        }

        $query->joinWith(['procuringEntity', 'items']);

        $query->andFilterWhere([
            'or',
            ['like', 'api_auctions.title', $this->main_search],
            ['like', 'api_auctions.auctionID', $this->main_search],
            ['like', 'api_organizations.name', $this->main_search],
            ['like', 'api_organizations.identifier_id', $this->main_search],
        ]);

        $query->andFilterWhere([
            'or',
            ['like', 'api_organizations.contactPoint_name', $this->org_name],
            ['like', 'api_organizations.name', $this->org_name],
        ]);

        $query->andFilterWhere([
            'status' => $this->status
        ]);
        $query->andFilterWhere(['like', 'api_items.address_region', $this->region]);
        $query->andFilterWhere(['like', 'api_items.classification_id', $this->cav]);
        $query->andFilterWhere(['api_auctions.procurementMethodType' => $this->type]);

        return $dataProvider;
    }

    public function isClear(){
        return !$this->status
            && !$this->region
            && !$this->cav
            && !$this->org_name
            && !$this->type
            && !$this->main_search;
    }
}
