<?php
namespace frontend\controllers;

use backend\models\GoodsCategory;
use yii\web\Controller;

class IndexController extends Controller{
    public $layout = 'index';


    public function actionIndex(){

        //$models=GoodsCategory::find()->all();
//        $models=GoodsCategory::find()->where(['depth'=>0])->all();
        $models=GoodsCategory::findAll(['parent_id'=>0]);

        return $this->render('index',['models'=>$models]);
    }

}