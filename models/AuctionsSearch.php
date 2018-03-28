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
    public $category;

    public function rules()
    {
        $rules =  [];

        $rules[] = [['main_search', 'region', 'org_name'], 'string', 'max' => 255];
        $rules[] = [['status'], 'in', 'range' => array_keys((new Auctions())->statusNames)];
        $rules[] = [['cav'], 'safe'];
        $rules[] = [['type'], 'in', 'range' => array_keys(Lots::$procurementMethodTypes)];
        $rules[] = [['category'], 'in', 'range' => array_keys($this->categories())];
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
            return $dataProvider;
        }

        $query->joinWith(['procuringEntity']);
        if($this->cav || $this->region || $this->category){
            $query->joinWith(['items']);
        }

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
        if($this->category){
            $categories = $this->categories()[$this->category];
            $conditions = ['or'];
            foreach($categories as$category){
                $conditions[] = ['like', 'api_items.classification_id', $category . '%', false];
            }
            $query->andWhere($conditions);
        }
        if(!YII_DEBUG){
            $query->andWhere(['not like', 'api_auctions.title', '[тестування]']);
        }

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

    private function categories(){
        return [
            'transport' => [
                '34'
            ],
            'live' => [
                '04100000-9',
                '04111000-9',
                '04111100-0',
                '04111200-1',
                '04111210-4',
                '04111220-7',
                '04112000-6',
                '04112100-7',
                '04112200-8',
                '04112300-9',
                '04113000-3',
                '04113100-4',
                '04113200-5',
                '04114000-0',
                '04114100-1',
                '04114200-2',
                '04115000-7',
                '04115100-8',
                '04115200-9',
            ],
            'notlive' => [
                '04200000-0',
                '04210000-3',
                '04211000-0',
                '04212000-7',
                '04213000-4',
                '04214000-1',
                '04220000-6',
                '04221000-3',
                '04222000-0',
                '04223000-7',
                '04224000-4',
                '04225000-1',
                '04230000-9',
                '04231000-6',
                '04232000-3',
                '04233000-0',
                '04234000-7',
            ],
            'commercial' => [
                '04210000-3',
                '04211000-0',
                '04212000-7',
                '04213000-4',
                '04214000-1',
                '04220000-6',
                '04221000-3',
                '04222000-0',
                '04223000-7',
                '04224000-4',
                '04225000-1',
            ],
            'areas' => [
                '06000000-2',
                '06010000-5',
                '06020000-8',
                '06030000-1',
                '06040000-4',
                '06050000-7',
                '06060000-0',
                '06070000-3',
                '06080000-6',
                '06090000-9',
                '06091000-6',
                '06092000-3',
            ],
            'techs' => [
                '30100000-0',
                '30200000-1',
                '301',
                '302',
            ]
        ];
    }
}
