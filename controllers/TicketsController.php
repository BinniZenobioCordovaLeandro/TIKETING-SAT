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

class TicketsController extends Controller
{
  public $defaultAction;

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
          ]
      ];
  }
  public function actionTicket()
  {
      $searchModel = new \app\models\search\TicketsSearchModel();
      $dataProvider = $searchModel->search(Yii::$app->request->get());
      return $this->render('ticket',[
          'searchModel' => $searchModel,
          'dataProvider' => $dataProvider
      ]);
  }
  public function actionTicketOperatore()
  {
      $searchModel = new \app\models\search\TicketsToOperatoreSearchModel();
      $dataProvider = $searchModel->search(Yii::$app->request->get());
      return $this->render('ticketOperatore',[
          'searchModel' => $searchModel,
          'dataProvider' => $dataProvider
      ]);
  }
  public function actionNotAssigned()
  {
      $searchModel = new \app\models\search\TicketsNotAssignedSearchModel();
      $dataProvider = $searchModel->search(Yii::$app->request->get());
      return $this->render('indexnotssigned',[
          'searchModel' => $searchModel,
          'dataProvider' => $dataProvider
      ]);
  }
   public function actionDetail($id)
  {
      $model =  \app\models\TicketsModel::find()->where(['id_ticket' => $id])->one();
      return $this->renderPartial('detail',[
          'id' => $id,
          'model' => $model,
      ]);
  }
}
