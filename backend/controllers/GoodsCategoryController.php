<?php

namespace backend\controllers;

use backend\models\GoodsCategory;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class GoodsCategoryController extends BackendController
{
    public function actionIndex()
    {
        $query=GoodsCategory::find();
        $total=$query->count();
        $page= new Pagination([
            'totalCount'=>$total,
            'pageSize'=>3
        ]);
        $model=$query->limit($page->limit)->offset($page->offset)->all();
        return $this->render('index',['model'=>$model,'page'=>$page]);
    }
    //添加商品分类
    public function actionAdd(){
        $model=new GoodsCategory();
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //判断添加的商品类别是否为顶级分类
                //parent_id是否为0
                if($model->parent_id){
                    //添加非顶级分类
                    $parent=GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($parent);
                }else{
                    //添加顶级分类
                    $model->makeRoot();
                }
                \Yii::$app->session->setFlash('success','添加商品分类成功');
                return $this->redirect(['goods-category/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        //获取所有分类
//        $category=ArrayHelper::map(GoodsCategory::find()->asArray()->all(),'id','name');
//        $category=GoodsCategory::find()->asArray()->all();
        $category=ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],GoodsCategory::find()->asArray()->all());
        return $this->render('add',['model'=>$model,'category'=>$category]);
    }
    //修改商品分类
    public function actionEdit($id){
        $model=GoodsCategory::findOne(['id'=>$id]);
        if($model==null){
            throw new NotFoundHttpException('分类不存在');
        }
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //判断添加的商品类别是否为顶级分类
                //parent_id是否为0
                if($model->parent_id){
                    //添加非顶级分类
                    $parent=GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($parent);
                }else{
                    //添加顶级分类
                    $model->makeRoot();
                }
                \Yii::$app->session->setFlash('success','修改商品分类成功');
                return $this->redirect(['goods-category/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        //获取所有分类
        $category=ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],GoodsCategory::find()->asArray()->all());
        return $this->render('add',['model'=>$model,'category'=>$category]);
    }

    //测试嵌套
    //https://github.com/somebody-hxy/php0217yii2shop.git
    public function actionTest(){
        //添加一级分类
        /*
        $model=new GoodsCategory();
        $model->name='家用电器';
        $model->parent_id=0;
        $model->intro='家用电器,更省心';
        $model->makeRoot();
        echo '成功';*/

        //添加多级分类
        /*$p=GoodsCategory::findOne(['id'=>1]);
        $model=new GoodsCategory();
        $model->name='大家电';
        $model->parent_id=$p->id;
        $model->intro='空调，冰箱，洗衣机';
        $model->prependTo($p);
        echo '成功';*/

        //获取当前分类下的所有子分类
        /*$p=GoodsCategory::findOne(['id'=>1]);
        $children=$p->leaves()->all();
        var_dump($children);*/
    }
    //测试ztree
    public function actionZtree(){
        $category=GoodsCategory::find()->asArray()->all();
        return $this->renderPartial('ztree',['category'=>$category]);
    }
}
