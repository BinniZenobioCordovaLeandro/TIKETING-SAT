<?php
namespace app\models;
use yii\db\ActiveRecord;

class TicketsModel extends ActiveRecord
{
   public static function tableName(){
           return 'ticketing.tickets';
   }
   public function rules()
  {
      return [
          // the name, email, subject and body attributes are required
          [['solleciti'], 'integer'],
      ];
  }
   public function attributeLabels()
    {
        return [
            'id_sorgente' => 'Sorgente',
            'id_ticket' => 'ID Ticket',
            'cliente_finale' => 'Cliente',
            'sede_richiedente' => 'Sede Richiedente',
            'ubicazione_richiedente' => 'Ubicazione Richiedente',
            'nominativo_richiedente' => 'Nominativo Richiedente',
            'email_richiedente' => 'Email Richiedente',
            'telefono_richiedente' => 'Telefono Richiedente',
            'altro_richiedente' => 'Altro Richiedente',
            'origine' => 'Origine',
            'tipo' => 'Tipo',
            'data_inserimento' => 'Data Inserimento',
            'data_modifica' => 'Ultima Modifica',
            'data_apertura' => 'Data Apertura',
            'data_assegnazione' => 'Data Assegnazione',
            'data_scadenza' => 'Data Scadenza',
            'stato' => 'Stato',
            'urgenza' => 'Urgenza',
            'impatto' => 'Impatto',
            'priorita' => 'Priorità',
            'slt' => 'SLA',
            'solleciti' => 'Sollecito',
            'categoria' => 'Categoria',
            'prodotto' => 'Prodotto',
            'oggetto' => 'Oggetto',
            'testo' => 'Testo',
            'note' => 'Note',
            'gruppi' => 'Gruppi',
            'operatore_gpi' => 'Operatore GPI',
            'operatore_cliente' => 'Operatore Cliente',
            'id_operatore_gpi' => 'Operatore Nick',
        ];
    }
    public function getSorgente()
    {
        return $this->hasOne(SorgenteModel::className(), [ 'id_sorgente' => 'id_sorgente']);
    }
    public function getImpatto()
    {
        return $this->hasOne(ImpattoModel::className(), [ 'id_impatto' => 'impatto']);
    }
    public function getOrigine()
    {
        return $this->hasOne(OrigineModel::className(), ['id_origine' => 'origine']);
    }
    public function getPriorita()
    {
        return $this->hasOne(PrioritaModel::className(), [ 'id_priorita' => 'priorita']);
    }
    public function getStato()
    {
        return $this->hasOne(StatoModel::className(), [ 'id_stato' => 'stato']);
    }
    public function getTipo()
    {
        return $this->hasOne(TipoModel::className(), [ 'id_tipo' => 'tipo']);
    }
    public function getUrgenza()
    {
        return $this->hasOne(UrgenzaModel::className(), [ 'id_urgenza' => 'urgenza' ]);
    }
    public function getOperatore(){
        return $this->hasOne(OperatoriModel::className(), ['id_operatore' => 'id_operatore_gpi']);
    }
}
