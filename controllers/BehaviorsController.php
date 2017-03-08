<?php
/**
 * Created by PhpStorm.
 * User: yaroslav
 * Date: 21.02.17
 * Time: 16:39
 */

namespace app\controllers;



use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;


class BehaviorsController extends Controller
{

    public function behaviors()
    {
        return [

            'access' => [
                'class' => AccessControl::className(),
                /*'denyCallback' => function ($rule, $action) {
                    throw new \Exception('Нет доступа.');
                },*/

                'rules' => [
                    [
                        'allow' => true,
                        'controllers' => ['main'],
                        'actions' => ['register', 'login', 'activate-account'],
                        'verbs' => ['GET', 'POST'],
                        'roles' => ['?']
                    ],
                    [
                        'allow' => true,
                        'controllers' => ['main'],
                        'actions' => ['logout'],
                        'verbs' => ['POST'],
                        'roles' => ['@']
                    ],

                    [
                        'allow' => true,

                        'actions' => ['index', 'search'],

                    ],




        ]

    ]

   ];

    }


}