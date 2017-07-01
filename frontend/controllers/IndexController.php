<?php
namespace frontend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsImg;
use yii\web\Controller;

class IndexController extends Controller{
    public $layout = 'index';


    public function actionIndex(){

        $models=GoodsCategory::findAll(['parent_id'=>0]);

        return $this->render('index',['models'=>$models]);
    }

    public function actionList(){
        $this->layout = 'list';
        $goodscategory=GoodsCategory::findAll(['parent_id'=>0]);
        $goods=Goods::find()->all();
        //$goods=Goods::findAll(['goods_category_id'=>$cate_id]);
        //return $this->render('list',['goodscategory'=>$goodscategory,'goods'=>$goods]);
        return $this->render('list',['goodscategory'=>$goodscategory,'goods'=>$goods]);
    }

    public function actionGoods($cate_id){
        $this->layout='goods';
        $goodscategory=GoodsCategory::findAll(['parent_id'=>0]);
        $goods1=Goods::findOne(['id'=>$cate_id]);
        $imgs=GoodsImg::findAll(['goods_id'=>$cate_id]);
        return $this->render('goods',['goodscategory'=>$goodscategory,'goods1'=>$goods1,'imgs'=>$imgs]);
    }

}