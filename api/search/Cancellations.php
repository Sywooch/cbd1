<?php

namespace api\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\Cancellations as CancellationsModel;

/**
 * Cancellations represents the model behind the search form about `api\Cancellations`.
 */
class Cancellations extends CancellationsModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['unique_id', 'relatedItem', 'created_at', 'updated_at'], 'integer'],
            [['id', 'reason', 'status', 'date', 'cancellationOf'], 'safe'],
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
        $query = CancellationsModel::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'unique_id' => $this->unique_id,
            'relatedItem' => $this->relatedItem,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'reason', $this->reason])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'date', $this->date])
            ->andFilterWhere(['like', 'cancellationOf', $this->cancellationOf]);

        return $dataProvider;
    }
}
