<?php

namespace app\models\search;

use app\models\TicketsModel;
use yii\data\ActiveDataProvider;

/**
 * Class UserSearch
 *
 * @package app\modules\admin\models\search
 */
class TicketsNotAssignedSearchModel extends TicketsModel
{

    public $datascadenza;

    public function rules(){
         return [
            [['urgenza', 'datascadenza','id_ticket', 'oggetto','operatore_gpi','cliente_finale','id_sorgente','priorita','stato','prodotto','categoria'], 'safe'],
        ];
    }
    /**
     * Creates data provider instance with search query applied
     *
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = TicketsModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => ['id_ticket' => SORT_DESC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $stato =1;

        $query->andFilterWhere([
            'urgenza' => $this->urgenza,
            'id_sorgente' => $this->id_sorgente,
            'priorita' => $this->priorita,
            'stato' => $stato,
        ]);
        $query->andFilterWhere(['like', 'id_ticket', $this->id_ticket]);

        $query->andFilterWhere(['like', 'oggetto', $this->oggetto]);
        $query->andFilterWhere(['like', 'prodotto', $this->prodotto]);
        $query->andFilterWhere(['like', 'categoria', $this->categoria]);
        $query->andFilterWhere(['like', 'operatore_gpi', $this->operatore_gpi]);
        $query->andFilterWhere(['like', 'cliente_finale', $this->cliente_finale]);

        if($this->datascadenza){
            $from=\DateTime::createFromFormat('d/m/Y',$this->datascadenza);
            $this->data_scadenza=$from->format('Y-m-d');
            $expression = new \yii\db\Expression('( data_scadenza::date )');
            $query->andFilterWhere(['=', $expression, $this->data_scadenza]);
        }
        return $dataProvider;
    }
}
