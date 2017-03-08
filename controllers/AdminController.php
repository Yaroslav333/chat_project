<?php
/**
 * Created by PhpStorm.
 * User: yaroslav
 * Date: 22.02.17
 * Time: 11:43
 */

namespace app\controllers;

use app\models\User;
use yii\web\Controller;
use Yii;
class AdminController extends Controller
{

   public function actionIndex()
   {

       $model = User::find()->all();


       if (Yii::$app->user->isGuest){

           return $this->redirect('/main/login');

       }

            if(Yii::$app->user->identity->role !== 1){

                return $this->redirect('/site/error');

            }

       return $this->render('index', compact('model'));


   }


}