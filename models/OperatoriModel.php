<?php
namespace app\models;

use yii\db\ActiveRecord;
use app\components\CachedActiveRecord;


use app\models\ProfiloModel;

/**
 * Class ImpattoModel
 *
 * @package  app\models;
 */
class OperatoriModel extends ActiveRecord implements \yii\web\IdentityInterface
{

  use CachedActiveRecord;

   public static function tableName(){
           return 'ticketing.operatori_gpi';
   }

   public function rules(){
      return [
          [['id_operatore'], 'id'],
          [['nome_operatore'], 'string'],
          [['servizio'], 'string'],
          [['attivo'], 'boolean'],
          [['codice'], 'string'],
          [['id_profilo'], 'integer'],
          [['show_in_calendar'],'integer'],
      ];
    }

  public function attributeLabels(){
       return [
           'id_operatore' => 'id',
           'nome_operatore' => 'nome',
           'servizio' => 'servizio',
           'attivo' => 'attivo',
           'codice' => 'codice',
           'id_profilo' => 'profilo',
           'show_in_calendar' => 'In Calendar'
       ];
   }
   public static function listData(){
     $arr=array();
     foreach (static::find()->select(['id_operatore', 'codice'])->all() as $operatori){
          $arr[$operatori->id_operatore]=$operatori->codice;
     }
     return $arr;
    }
    public static function getLabel($id){
      $obj = (static::findOne(['id_operatore' => $id]));
      if(is_null($obj)) return "$id";
      return $obj->codice;
    }
   public function getOperatore($codice){
       $OperatoriModel = (static::findOne(['codice' => $codice]));
       if(is_null($OperatoriModel)) return null;
       return new static($OperatoriModel);
   }
   public function getOperatoreById($id){
       $OperatoriModel = (static::findOne(['id_operatore' => $id]));
       if(is_null($OperatoriModel)) return null;
       return $OperatoriModel;
   }
   public function getIdOperatore($nomeOperatore){
     $obj = (static::findOne(['nome_operatore' => $nomeOperatore]));
     if(is_null($obj)) return "$nomeOperatore";
     return $obj->id_operatore;
   }
   public function getNomeOperatoreById($id){
       $OperatoriModel = (static::findOne(['id_operatore' => $id]));
       if(is_null($OperatoriModel)) return null;
       return $OperatoriModel->nome_operatore;
   }

   // public function getIdProfilo($id){
   //     $OperatoriModel = (static::findOne(['id_operatore' => $id]));
   //     if(is_null($OperatoriModel)) return "Profilo non Trovato";
   //     return $OperatoriModel->id_profilo;
   // }

   // public function getProfilo()
   // {
   //     return $this->hasOne(ProfiloModel::className(),['id' => 'id_profilo']);
   // }

   public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['codice' => $token]);
    }
    public function getId()
    {
        return $this->id_operatore;
    }
    public function getCodice()
    {
        return $this->codice;
    }
    public function getAuthKey()
    {
        return $this->codice;
    }
    public function getNome()
    {
        return $this->nome_operatore;
    }
    public function validateAuthKey($codice)
    {
        return $this->codice === $codice;
    }
}
