<?php
/**
 * Created by PhpStorm.
 * User: yaroslav
 * Date: 01.03.17
 * Time: 11:18
 */

namespace app\controllers;


use yii\web\Controller;

class TestController extends Controller
{

    public function actionIndex()
    {

        return $this->render('index');


    }

}