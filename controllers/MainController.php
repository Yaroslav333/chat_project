<?php
/**
 * Created by PhpStorm.
 * User: yaroslav
 * Date: 20.02.17
 * Time: 15:37
 */

namespace app\controllers;

use app\models\LoginForm;

use yii\web\Controller;
use app\models\RegForm;
use Yii;
use app\models\User;
use app\commands\SocketServer;



class MainController extends BehaviorsController
{



    public function actionIndex()
    {



        $user = User::find()->select('role')->all();

        if(Yii::$app->user->isGuest):
           // if(Yii::$app->user)
            return $this->redirect('/main/login');
         endif;
        return $this->render('index', compact('user'));

    }



    public function actionRegister()
    {

        $model = new RegForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()):
            if($user = $model->reg()):
                if ($user->status === User::STATUS_ACTIVE):
                    if (Yii::$app->getUser()->login($user)):


                    return $this->goHome();

                    endif;
                endif;
            else:
                Yii::$app->session->setFlash('error', 'Возникла ошибка при регистрации.');
                Yii::error('Ошибка при регистрации');
                return $this->refresh();


            endif;

         endif;


        return $this->render('reg',['model' => $model]);
    }



    public function actionLogout()
    {


        Yii::$app->user->logout();
        return $this->redirect(['/main/index']);
    }



    public function actionLogin()
    {


        if (!Yii::$app->user->isGuest):

            return $this->goHome();

        endif;



        $model = new LoginForm();

        $session = Yii::$app->session;

        if($model->load(Yii::$app->request->post()) && $model->login()):
            return $this->redirect(['/main/index']);


        endif;


        return $this->render('login',['model' => $model]);
    }




}