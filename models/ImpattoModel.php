<?php

namespace app\models;

use yii\db\ActiveRecord;
use app\components\CachedActiveRecord;

/**
 * Class ImpattoModel
 *
 * @package  app\models;
 */
class ImpattoModel extends ActiveRecord
{

   use CachedActiveRecord;

   public static function tableName(){
           return 'ticketing.impatto';
   }

   public function getTickets()
   {
       return $this->hasMany(TicketsModel::className(), ['impatto' => 'id_impatto'])->all();
   }

}
