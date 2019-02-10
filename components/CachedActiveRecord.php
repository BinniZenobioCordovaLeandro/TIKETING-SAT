<?php
/**
 * Created by PhpStorm.
 * User: samuelerenati
 * Date: 20/07/17
 * Time: 09:05
 */

namespace app\components;

use Yii;
/**
 * CachedActiveRecord is the ActiveRecord class with cache
 */
trait CachedActiveRecord
{
    /**
     * @inheritdoc
     * @return CachedActiveQuery the newly created [[CachedActiveQuery]] instance.
     */
    public static function find()
    {
        return Yii::createObject([
            'class'      => CachedActiveQuery::className(),
            'duration'   => 2,
            'dependency' => null
        ], [get_called_class()]);
    }
}
