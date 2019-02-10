<?php
namespace app\controllers;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\SorgenteModel;
use app\models\OperatoriModel;
use app\models\ImpattoModel;
use app\models\ProfiloModel;
use app\models\CalendarModel;
use app\models\AttivitaOperatoreModel;

// cookies control!
use yii\web\CookieCollection;
use  yii\web\Cookie;


use app\models\DashboardModel;
use app\models\StatoModel;
use app\models\PrioritaModel;

class SiteController extends Controller
{
  public $defaultAction;
  public function getActionHome(){
    $home="";
    if (isset(Yii::$app->user->id)) {
      $cookies = Yii::$app->response->cookies;
      if(isset($cookies[Yii::$app->user->id."_home"]) && !empty($cookies[Yii::$app->user->id."_home"])){
        $home = $cookies[Yii::$app->user->id."_home"];
        return $home;
      }else {
        // home:
        $operatore = OperatoriModel::getOperatoreById(Yii::$app->user->id);
        if ($operatore->id_profilo) {
          $funzionalita = ProfiloModel::getFunzionalita($operatore->id_profilo);
          foreach ($funzionalita as $key => $value) {

            $cookies->add(new Cookie([
                'name' => Yii::$app->user->id."_home",
                'value' => $home,
                'expire' => time()-3600
            ]));

            $home = $value['path'];
            return $home;
          }
        }
      }
    }
  }

  public function getDefaultAction(){
    if (isset(Yii::$app->user->id)) {
      $azioni =self::getActions();
      $defaultActionI = reset($azioni);
      if ($defaultActionI) {
        $this->defaultAction = $defaultActionI;
      }
    }
  }
  public function getActions(){
    $actions = array();
    if (isset(Yii::$app->user->id)) {
      $cookies = Yii::$app->response->cookies;
        if (isset($cookies[Yii::$app->user->id]) && !empty($cookies[Yii::$app->user->id])) {
          // prende il valore della cookie presa per il ID e la mette in $actions.
          $actions = $cookies[Yii::$app->user->id]->value;
        }else {
          // fa la creazione di una cookie secondo:
          $operatore = OperatoriModel::getOperatoreById(Yii::$app->user->id);
          if ($operatore->id_profilo) {
            $funzionalita = ProfiloModel::getFunzionalita($operatore->id_profilo);
            foreach ($funzionalita as $key => $value) {
              array_push($actions, $value['codice']);
            }
          }
          array_push($actions, 'logout');
          array_push($actions, 'login');
          array_push($actions, 'detail');
          $cookies->add(new \yii\web\Cookie([
              'name' => Yii::$app->user->id,
              'value' => $actions,
              'expire' => time()-3600,
          ]));
        }
    }
    return $actions;
  }
  public function behaviors()
  {
    $this->getDefaultAction();
      return [
          'access' => [
              'class' => AccessControl::className(),
              'rules' => [
                  [
                      'actions' => $this->getActions(),
                      'allow' => true,
                      'roles' => ['@'],
                  ],
                  [
                      'allow' => true,
                      'actions' => ['login','logout'],
                      'roles' => ['?'],
                  ],
              ],
              'denyCallback'  => function ($rule, $action) {
                Yii::$app->session->setFlash('error', 'This section is only for registered users.');
                Yii::$app->user->loginRequired();
              },
          ],
          'verbs' => [
              'class' => VerbFilter::className(),
              'actions' => [
                  'logout' => ['post'],
              ],
          ],
      ];
    }
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
    public function actionDash()
    {
      $sorgente = SorgenteModel::find()->all();
      $elencoStati = StatoModel::find()->where(['close'=>false])->orderBy('order')->all();
      $elencoPriorita = PrioritaModel::find()->orderBy('order')->all();
      $datiDash = DashboardModel::find()->all();

      return $this->render('dash',[
        'sorgente'=>$sorgente,
        'elencoStati' =>$elencoStati,
        'elencoPriorita' => $elencoPriorita,
        'datiDash' => $datiDash
      ]);
    }
    public function actionAnalisiope()
    {
        $elencoStati = \app\models\StatoModel::find()->where(['show_situazione_operatore'=>true])->orderBy('order')->all();
        return $this->render('tableope',[
            'stati' =>$elencoStati
        ]);
    }
    public function actionAnalisicli()
    {
      $searchModel = new \app\models\search\SituazioneClientiSearchModel();
      $dataProvider = $searchModel->search(Yii::$app->request->get());
      return $this->render('tablecli',[
          'searchModel' => $searchModel,
          'dataProvider' => $dataProvider
      ]);
    }

    public function actionOperatori()
    {
      $countEvents = 0;
      $events = [];
      $meseFocus = date('Y-m-d',strtotime('+1 day',strtotime(date('Y-m-d'))));
      // return $meseFocus;
      $dataDa = date('Y-m-d',strtotime('-1 year',strtotime($meseFocus)));
      $dataA = date('Y-m-d',strtotime('+1 year',strtotime($meseFocus)));

      $operatori = OperatoriModel::find()->select(['codice'])->where(['show_in_calendar'=>1])->orderby('codice')->all();
      $daStampare ="";
      foreach ($operatori as $operatore) {
        $daStampare .= "[".$operatore->codice."]\n";
      }
      // return $daStampare;
      $AttivitaOperatori = AttivitaOperatoreModel::find()->select(['operatore','data_attivita','ore_attivita'])->where(['titolo_attivita'=>array('FERIE','PERMESSO'),'stato'=>'Pianificato'])->where(['between','data_attivita',$dataDa,$dataA])->all();

      $vacanze = CalendarModel::find()->select(['CALENDAR_DATE'])->where(['WEEK_DAY_NUM'=>array(1,2,3,4,5),'IS_HOLIDAY'=>1])->all();
      $dataControllo = $dataDa;
      while ($dataControllo <= $dataA) {
        if (array_search($dataControllo, array_column($vacanze,'CALENDAR_DATE'))) {
          $countEvents = $countEvents + 1;
          $eventHoliday = new \yii2fullcalendar\models\Event();
          $eventHoliday->id = $countEvents;
          $eventHoliday->title ="Holiday !!!";
          $eventHoliday->start = date('Y-m-d',strtotime($dataControllo));
          $eventHoliday->color = "#ffb3b3";
          $eventHoliday->rendering = "background";
          $events[] = $eventHoliday;
        }else {
          foreach ($operatori as $operatore) {
            $ore = '08:00:00';
            $color = '#b3ff66';
            foreach ($AttivitaOperatori as $attivita) {
              if ($attivita->data_attivita == $dataControllo && $attivita->operatore == $operatore->codice) {

                $ore = $this->sottrarreOre($attivita->ore_attivita,$ore);
                if ($ore < date("H:i:s",strtotime("08:00"))) {
                  $color = '#a0a0a0';
                  if ($ore < date("H:i:s",strtotime("00:30"))) {
                    $color = 'white';
                  }
                }
              }
            }
            $ore = substr($ore,0,5);
            $countEvents = $countEvents + 1;
            $eventOperatore = new \yii2fullcalendar\models\Event();
            $eventOperatore->id = $countEvents;
            $eventOperatore->title = "(".$ore.")\t".$operatore->codice."";
            $eventOperatore->start = date('Y-m-d',strtotime($dataControllo));
            $eventOperatore->color = $color;
            $events[] = $eventOperatore;
          }
        }
        $dataControllo = date('Y-m-d',strtotime('+1 day',strtotime($dataControllo)));
      }
      return $this->render('operatori', [
          'events' => $events,
          'countEvents' => $countEvents,
      ]);
    }

    public function actionLogin($nomeutenteremedy = null)
    {
      if (isset(Yii::$app->user->id) && !$nomeutenteremedy) {
        // $operatore = OperatoriModel::getOperatoreById(Yii::$app->user->id);
        // if (!$operatore->id_profilo) {
        //   return $this->render('errorLogin',[
        //       'messagio' => 'Non hai un profilo definito. :\'( '
        //   ]);
        // };
        return $this->redirect([$this->getActionHome()]);
      }
      if (!$nomeutenteremedy)
      return $this->render('errorLogin',[
          'messagio' => 'Non possiamo fare un login si non c\'è un utente messo nella URL.'
      ]);
      $operatore = OperatoriModel::getOperatore($nomeutenteremedy);
      if(!$operatore){
        Yii::$app->user->logout();
        Yii::$app->session->destroy();
        return $this->render('errorLogin',[
          'messagio' => 'Hai sbagliato il tuo UTENTE !, quindi non ti posso mostrare niente :\'( .'
        ]);
      }
      Yii::$app->user->login($operatore,-3600);
      return $this->redirect([$this->getActionHome()]);
    }

    public function actionLogout()
    {
        // Yii::$app->user->logout();
        Yii::$app->session->destroy();
        return $this->redirect(['site/login']);
    }
    public function actionShow(){
    }

    public function actionTicket(){
      return $this->redirect(['tickets/ticket']);
    }
    public function actionNotAssigned(){
      return $this->redirect(['tickets/not-assigned']);
    }

    public function sottrarreOre($horaini,$horafin)
    {
      $horai=substr($horaini,0,2);
      $mini=substr($horaini,3,2);
      $segi=substr($horaini,6,2);

      $horaf=substr($horafin,0,2);
      $minf=substr($horafin,3,2);
      $segf=substr($horafin,6,2);

      $ini=((($horai*60)*60)+($mini*60)+$segi);
      $fin=((($horaf*60)*60)+($minf*60)+$segf);

      $dif=$fin-$ini;

      $difh=floor($dif/3600);
      $difm=floor(($dif-($difh*3600))/60);
      $difs=$dif-($difm*60)-($difh*3600);
      return date("H:i:s",mktime($difh,$difm,$difs));
    }
}
