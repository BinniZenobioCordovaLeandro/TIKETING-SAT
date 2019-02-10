<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\components\CachedActiveRecord;

/**
 * Class SorgenteModel
 *
 * @package  app\models;
 */
class SorgenteModel extends ActiveRecord
{

   use CachedActiveRecord;

   public static function tableName(){
           return 'ticketing.sorgente';
   }

   public static function listData(){
       $arr=array();
       foreach (static::find()->select(['id_sorgente', 'codice'])->all() as $client){
            $arr[$client->id_sorgente]=$client->codice;
       }
       return $arr;
    }

    public static function getLabel($id){
        $obj = (static::findOne(['id_sorgente' => $id]));
        if(is_null($obj)) return "$id";
        return $obj->codice ;
    }

   public function getTickets()
   {
       return $this->hasMany(TicketsModel::className(), ['id_sorgente' => 'id_sorgente'])->all();
   }

   public function getElaborazione()
   {
       return $this->hasOne(UltimaElaborazioneModel::className(), ['id_sorgente' => 'id_sorgente']);
   }

   public function getRiepTicket()
   {
        $sql = "SELECT stato, s.order, s.codice as scodice, priorita , p.order, p.codice , p.color ,count(*) as cnt
	FROM ticketing.tickets inner join ticketing.stato s on stato=id_stato
	inner join ticketing.priorita p on priorita = id_priorita
	where close=false and id_sorgente= :idsorgente
	group by stato, s.order, s.codice, priorita , p.order, p.codice, p.color
	order by s.order , p.order";

        $idsorg = $this->id_sorgente;
        $command= Yii::$app->db->createCommand($sql);
        $command->bindParam(':idsorgente', $idsorg);
        $result = $command->queryAll();
        return $result;
   }

   public static function getTicketTotal($sorgente,  $stato, $priorita)
   {

       if($priorita){
        $sql = "SELECT count(*) as cnt
              	FROM ticketing.tickets
              	where id_sorgente= :idsorgente and
                stato = :idstato and priorita = :idpriorita";

        // id_sorgente
        // id_priorita
        // id_stato

        $command= Yii::$app->db->createCommand($sql);
        $command->bindParam(':idsorgente', $sorgente);
        $command->bindParam(':idstato', $stato);
        $command->bindParam(':idpriorita', $priorita);
        $cnt = $command->queryScalar();
        if ( $cnt ){
            return $cnt;
        }else{
            return '';
        }
       }else{
           $sql = "SELECT count(*) as cnt
	FROM ticketing.tickets
	where id_sorgente= :idsorgente and
        stato = :idstato";

        // id_sorgente
        // id_priorita
        // id_stato

        $command= Yii::$app->db->createCommand($sql);
        $command->bindParam(':idsorgente', $sorgente);
        $command->bindParam(':idstato', $stato);

        $cnt = $command->queryScalar();
        if ( $cnt ){
            return $cnt;
        }else{
            return '';
        }
       }
   }

}
