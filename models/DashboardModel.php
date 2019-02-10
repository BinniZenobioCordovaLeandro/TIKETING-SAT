<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\components\CachedActiveRecord;

class DashboardModel extends ActiveRecord
{
    use CachedActiveRecord;
    public static function tableName()
    {
        return 'ticketing.show_dashboard';
    }
}
