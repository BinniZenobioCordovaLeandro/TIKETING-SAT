<?php

namespace app\models;

use Yii;

use yii\db\ActiveRecord;
use app\components\CachedActiveRecord;

class ProfiloModel extends ActiveRecord
{
   use CachedActiveRecord;

   public static function tableName(){
           return 'ticketing.profili';
   }

   // public function getTickets(){
   //     return $this->hasMany(TicketsModel::className(), ['impatto' => 'id_impatto'])->all();
   // }

   // public function getFunzionalita()
   // {
   //   $sql = 'select * from funzionalità f where f.id in (select rel.id_funz from rel_profili_funz rel where id_profilo = :id) order by "order"';
   //      // $sql = "SELECT stato, s.order, s.codice as scodice, priorita , p.order, p.codice , p.color ,count(*) as cnt
   //    	// FROM ticketing.tickets inner join ticketing.stato s on stato=id_stato
   //    	// inner join ticketing.priorita p on priorita = id_priorita
   //    	// where close=false and id_sorgente= :idsorgente
   //    	// group by stato, s.order, s.codice, priorita , p.order, p.codice, p.color
   //    	// order by s.order , p.order";
   //      $id = $this->id;
   //      $command= Yii::$app->db->createCommand($sql);
   //      $command->bindParam(':id', $id);
   //      $result = $command->queryAll();
   //      return $result;
   // }

   public function getActionsProfilo($id){
     // return a array.
   }

   public function getFunzionalita($id){
     // return records.
        $sql = 'select * from funzionalità f where f.id in (select rel.id_funz from rel_profili_funz rel where id_profilo = :id) order by "order"';
        $command= Yii::$app->db->createCommand($sql);
        $command->bindParam(':id', $id);
        $result = $command->queryAll();
        return $result;
   }
}
