<?php
namespace backend\controllers;

use backend\models\Goods;
use yii\web\Controller;

class GoodsController extends Controller{

    public function actionIndex(){
        return $this->render('index');
    }
    //添加数据
    public function actionAdd(){
        $model=new Goods();

        return $this->render('add',['model'=>$model]);
    }

}