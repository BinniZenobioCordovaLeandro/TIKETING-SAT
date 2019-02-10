<?php

namespace app\models;

use yii\db\ActiveRecord;
use app\components\CachedActiveRecord;

/**
 * Class TipoModel
 *
 * @package  app\models;
 */
class TipoModel extends ActiveRecord
{
   use CachedActiveRecord;

   public static function tableName(){
           return 'ticketing.tipo';
   }

   public function getTickets()
   {
       return $this->hasMany(TicketsModel::className(), ['tipo' => 'id_tipo'])->all();
   }

}
