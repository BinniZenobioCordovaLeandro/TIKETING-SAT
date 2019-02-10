<?php

// comment out the following two lines when deployed to production
// My Note : questi due rigè sono per vedere la consola de Yii
defined('YII_ENV') or define('YII_ENV', 'dev');
defined('YII_DEBUG') or define('YII_DEBUG', true);
// Endm my Note :

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';
use yii\web\Session;

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
