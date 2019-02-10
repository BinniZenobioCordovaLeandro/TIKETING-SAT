<?php
use yii\helpers\Html;

use yii\bootstrap\Alert;
use yii\bootstrap\Widget;

use yii\base\BootstrapWidgetTrait;

Alert::begin([
    'options' => [
        'class' => 'alert-warning',
    ],
]);

echo Html::encode($messagio);
Alert::end();
 ?>
