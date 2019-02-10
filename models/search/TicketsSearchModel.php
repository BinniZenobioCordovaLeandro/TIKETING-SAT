<?php
namespace app\models\search;
use app\models\TicketsModel;
use yii\data\ActiveDataProvider;
class TicketsSearchModel extends TicketsModel
{
    public $datascadenza;
    public $datamodifica;
    public $dataapertura;
    public $solleciti;
    public function rules(){
         return [
            [['urgenza', 'datascadenza','id_ticket','gruppi','datamodifica', 'solleciti','dataapertura','oggetto','operatore_gpi','cliente_finale','id_sorgente','priorita','stato','id_operatore_gpi'], 'safe'],
        ];
    }
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
        $query->andFilterWhere([
            'urgenza' => $this->urgenza,
            'id_sorgente' => $this->id_sorgente,
            'priorita' => $this->priorita,
            'stato' => $this->stato,
        ]);
        $query->andFilterWhere(['like', 'id_ticket', $this->id_ticket]);

        $query->andFilterWhere(['like', 'oggetto', $this->oggetto]);
        $query->andFilterWhere(['like', 'operatore_gpi', $this->operatore_gpi]);
        $query->andFilterWhere(['like', 'cliente_finale', $this->cliente_finale]);
        $query->andFilterWhere(['like', 'gruppi', $this->gruppi]);
        $query->andFilterWhere(['like', 'data_modifica', $this->data_modifica]);
        $query->andFilterWhere(['=','id_operatore_gpi', $this->id_operatore_gpi]);
        if($this->solleciti){
          if (is_numeric($this->solleciti)) {
            $query->andFilterWhere(['=', 'solleciti', $this->solleciti]);
          }else {
            $this->solleciti = '';
          }
        }
        $query->andFilterWhere(['like', 'data_apertura', $this->data_apertura]);
        if($this->datascadenza){
            $from=\DateTime::createFromFormat('d/m/Y',$this->datascadenza);
            $this->data_scadenza=$from->format('Y-m-d');
            $expression = new \yii\db\Expression('( data_scadenza::date )');
            $query->andFilterWhere(['=', $expression, $this->data_scadenza]);
        }
        if($this->datamodifica){
            $from=\DateTime::createFromFormat('d/m/Y',$this->datamodifica);
            $this->data_modifica=$from->format('Y-m-d');
            $expression = new \yii\db\Expression('( data_modifica::date )');
            $query->andFilterWhere(['=', $expression, $this->data_modifica]);
        }
        if($this->dataapertura){
            $from=\DateTime::createFromFormat('d/m/Y',$this->dataapertura);
            $this->data_apertura=$from->format('Y-m-d');
            $expression = new \yii\db\Expression('( data_apertura::date )');
            $query->andFilterWhere(['=', $expression, $this->data_apertura]);
        }
        return $dataProvider;
    }
}
