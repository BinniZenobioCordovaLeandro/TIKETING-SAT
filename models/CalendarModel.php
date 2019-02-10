<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ticketing.t_calendar".
 *
 * @property string $CALENDAR_DATE
 * @property string $WEEK_DAY
 * @property string $WEEK_DAY_NUM
 * @property string $CALENDAR_YEAR
 * @property string $CALENDAR_MONTH
 * @property string $CALENDAR_DAY
 * @property string $IS_HOLIDAY
 */
class CalendarModel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ticketing.t_calendar';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['CALENDAR_DATE', 'WEEK_DAY', 'WEEK_DAY_NUM', 'CALENDAR_YEAR', 'CALENDAR_MONTH', 'CALENDAR_DAY'], 'required'],
            [['CALENDAR_DATE'], 'safe'],
            [['WEEK_DAY_NUM', 'CALENDAR_YEAR', 'CALENDAR_MONTH', 'CALENDAR_DAY', 'IS_HOLIDAY'], 'number'],
            [['WEEK_DAY'], 'string', 'max' => 20],
            [['CALENDAR_DATE'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'CALENDAR_DATE' => 'Calendar  Date',
            'WEEK_DAY' => 'Week  Day',
            'WEEK_DAY_NUM' => 'Week  Day  Num',
            'CALENDAR_YEAR' => 'Calendar  Year',
            'CALENDAR_MONTH' => 'Calendar  Month',
            'CALENDAR_DAY' => 'Calendar  Day',
            'IS_HOLIDAY' => 'Is  Holiday',
        ];
    }
}
