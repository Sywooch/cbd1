<?php

namespace api\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\Auctions as AuctionsModel;

/**
 * Tenders represents the model behind the search form about `api\Tenders`.
 */

class Auctions extends AuctionsModel
{

    public $organization;

    public function rules()
    {
        return [
            [[
                'auctionID',
                'id',
                'access_token',
                'awardCriteria',
                'dateModified',
                'title',
                'description',
                'tenderID',
                'procuringEntity_kind',
                'procurementMethod',
                'procurementMethodType',
                'owner',
                'value_currency',
                'guarantee_currency',
                'date',
                'minimalStep_currency',
                'enquiryPeriod_startDate',
                'enquiryPeriod_endDate',
                'tenderPeriod_startDate',
                'tenderPeriod_endDate',
                'auctionPeriod_startDate',
                'auctionPeriod_endDate',
                'auctionUrl',
                'awardPeriod_startDate',
                'awardPeriod_endDate',
                'status',
                'organization',
            ],
                'safe'],

            [[
                'unique_id',
                'procuringEntity_id',
                'value_valueAddedTaxIncluded',
                'minimalStep_valueAddedTaxIncluded',
                'created_at',
                'updated_at',
            ],
                'integer'],

//            [['value_amount', 'guarantee_amount', 'minimalStep_amount'], 'number'],
        ];
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
        $query = AuctionsModel::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
            ],
        ]);

        $dataProvider->sort->attributes['organization'] = [
            'asc' => ['api_organizations.name' => SORT_ASC],
            'desc' => ['api_organizations.name' => SORT_DESC],
        ];

        $this->load($params);

        // вывод только опубликованных аукционов
        $query->andWhere(['!=', 'auctionID', '']);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->joinWith(['procuringEntity']);

        $query->andFilterWhere([
            'unique_id' => $this->unique_id,
            'procuringEntity_id' => $this->procuringEntity_id,
//            'value_amount' => $this->value_amount,
//            'guarantee_amount' => $this->guarantee_amount,
//            'minimalStep_amount' => $this->minimalStep_amount,
            'value_valueAddedTaxIncluded' => $this->value_valueAddedTaxIncluded,
            'minimalStep_valueAddedTaxIncluded' => $this->minimalStep_valueAddedTaxIncluded,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'auctionID', $this->auctionID])
            ->andFilterWhere(['like', 'access_token', $this->access_token])
            ->andFilterWhere(['like', 'awardCriteria', $this->awardCriteria])
            ->andFilterWhere(['like', 'dateModified', $this->dateModified])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'tenderID', $this->tenderID])
            ->andFilterWhere(['like', 'procuringEntity_kind', $this->procuringEntity_kind])
            ->andFilterWhere(['like', 'procurementMethod', $this->procurementMethod])
            ->andFilterWhere(['like', 'procurementMethodType', $this->procurementMethodType])
            ->andFilterWhere(['like', 'owner', $this->owner])
            ->andFilterWhere(['like', 'value_currency', $this->value_currency])
            ->andFilterWhere(['like', 'guarantee_currency', $this->guarantee_currency])
            ->andFilterWhere(['like', 'date', $this->date])
            ->andFilterWhere(['like', 'minimalStep_currency', $this->minimalStep_currency])
            ->andFilterWhere(['like', 'enquiryPeriod_startDate', $this->enquiryPeriod_startDate])
            ->andFilterWhere(['like', 'enquiryPeriod_endDate', $this->enquiryPeriod_endDate])
            ->andFilterWhere(['like', 'tenderPeriod_startDate', $this->tenderPeriod_startDate])
            ->andFilterWhere(['like', 'tenderPeriod_endDate', $this->tenderPeriod_endDate])
            ->andFilterWhere(['like', 'auctionPeriod_startDate', $this->auctionPeriod_startDate])
            ->andFilterWhere(['like', 'auctionPeriod_endDate', $this->auctionPeriod_endDate])
            ->andFilterWhere(['like', 'auctionUrl', $this->auctionUrl])
            ->andFilterWhere(['like', 'awardPeriod_startDate', $this->awardPeriod_startDate])
            ->andFilterWhere(['like', 'awardPeriod_endDate', $this->awardPeriod_endDate])
            ->andFilterWhere(['like', 'api_organizations.name', $this->organization])
        ;

        return $dataProvider;
    }
}
