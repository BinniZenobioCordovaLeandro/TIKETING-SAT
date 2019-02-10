<?php
use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Url;
use yii\bootstrap\Modal;

use app\models\OperatoriModel;
use app\models\FunzionalitaModel;
use app\models\ProfiloModel;

use yii\web\CookieCollection;
use  yii\web\Cookie;

AppAsset::register($this);

if (class_exists('ramosisw\CImaterial\web\MaterialAsset')) {
    ramosisw\CImaterial\web\MaterialAsset::register($this);
}
function isActiveMenuItem($controller, $action){
    if($controller === Yii::$app->controller->id){
        if($action === Yii::$app->controller->action->id ){
            return true;
        }
    }
    return false;
}
$descriptionPage['site/login'] = 'Login';
$testoLogoutExit = 'Logout';
$nick = '<div class="userNick red"><a class="textUserNick"><i class="material-icons">lock</i>Accesso Negato !</a></div>';

if (isset(Yii::$app->user->id)) {
  $cookies = Yii::$app->response->cookies;
  if ((isset($cookies[Yii::$app->user->id."_nome"]) && !empty($cookies[Yii::$app->user->id."_nome"]))&&(isset($cookies[Yii::$app->user->id."_main"]) && !empty($cookies[Yii::$app->user->id."_main"]))) {
    $nick = $cookies[Yii::$app->user->id."_nome"];
    $funzionalita = $cookies[Yii::$app->user->id."_main"];
  }else {
    $userId = Yii::$app->user->id;
    $operatore = OperatoriModel::getOperatoreById($userId);
    if ($operatore) {
      $userName = ucwords(strtolower($operatore->nome_operatore));
      $userNameExplode = explode(" ",$userName);

      $nick = '<div class="userNick"><a class="textUserNick"><i class="material-icons">verified_user</i>'.HTML::encode($userNameExplode[0]).' '.HTML::encode($userNameExplode[1]).'</a></div>';
      if($nick){
        $cookies->add(new Cookie([
                    'name' => Yii::$app->user->id."_nome",
                    'value' => $nick,
                    'expire' => time()-3600
                ]));
      }

      $testoLogoutExit = 'Logout';

      if ($operatore->id_profilo) {
        $funzionalita = ProfiloModel::getFunzionalita($operatore->id_profilo);
        $cookies->add(new Cookie([
          'name' => Yii::$app->user->id."_main",
          'value' => $funzionalita,
          'expire' => time()-3600
        ]));
      }
    }
  }
}
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <!-- <meta http-equiv="refresh" content="5; url=http://10.168.21.210/" /> -->
        <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge"> -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title>GPI TICKETING</title>
        <?php $this->head() ?>
        <style>
            .modal-backdrop {display: none;}
            .modal {background: rgba(0,0,0,0.5);}
        </style>
        <link rel="shortcut icon" href="/img/fevicon.png"/>
    </head>
    <body>
        <?php $this->beginBody() ?>

        <div class="wrapper">

            <div class="sidebar" data-color="berry" data-image="../assets/img/sidebar-1.jpg">
                <div class="logo">
                    <a href="" class="simple-text">
                      <img src="/img/fevicon.png" alt="fevicon GPI Group">
                        GPI Ticketing
                    </a>
                </div>
                <div class="sidebar-wrapper">
                  <?php echo $nick; ?>
                    <ul class="nav">

                      <?php
                        if (isset($funzionalita) && !empty($funzionalita)) {
                          foreach ($funzionalita as $key => $value) {
                            // la 'V' solo è per diferenziare questi variabile dal resto !
                            $controllerActionV = explode("/",$value['path']);
                            $controllerV = $controllerActionV[0];
                            $ActionV = $controllerActionV[1];

                            echo "<li ";
                            if(isActiveMenuItem($controllerV, $ActionV)){ echo'class="active"'; };
                            echo ">";
                              echo "<a ";
                              echo "href=\"".Url::to([$value['path']])."\"";
                              echo ">";
                                echo "<i class=\"material-icons\">";
                                  echo $value['material_icon'];
                                echo "</i>";
                                echo "<p>";
                                  echo $value['descrizione'];
                                echo "</p>";
                              echo "</a>";
                            echo "</li>";
                            $descriptionPage[$value['path']]=$value['descrizione'];
                          }
                        }
                       ?>
                          <li class="logout">
                              <?= Html::a('<i class="material-icons" style="color:white">exit_to_app</i><p>'.$testoLogoutExit.'</p>', Url::to(['site/logout']), ['data-method' => 'POST']) ?>
                          </li>
                    </ul>
                </div>
            </div>

            <div class="main-panel">

                <nav class="navbar navbar-transparent navbar-absolute">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle" data-toggle="collapse">
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a class="navbar-brand" href="#"><?= $descriptionPage[Yii::$app->controller->id.'/'.Yii::$app->controller->action->id] ?></a>
                        </div>
                    </div>
                </nav>

                <div class="content">
                    <div class="container-fluid">
                         <?php echo $content; ?>
            </div>
        </div>
        <?php
            Modal::begin([
                'closeButton' => [
                  'label' => 'Chiudi',
                  'class' => 'btn btn-danger btn-sm pull-right',
                ],
                'size' => 'modal-lg',
                'id' => 'modal',
            ]);
             Modal::end();
        ?>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage()?>
