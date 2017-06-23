<?php

namespace frontend\controllers;

use frontend\models\Address;
use chenkby\region\RegionAction;
use frontend\models\Locations;
use backend\models\GoodsCategory;
class AddressController extends \yii\web\Controller
{

    public $layout = 'address';
    //添加数据
    public function actionIndex(){
        $member_id=\Yii::$app->user->id;
        $model=new Address();
        $models=Address::findAll(['member_id'=>$member_id]);
        $goodscategory=GoodsCategory::findAll(['parent_id'=>0]);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->is_default_address){
                foreach ($models as $v){
                    $v->is_default_address=1;
                    $v->save();
                }
            }
            $model->member_id=$member_id;
            $model->save();
            \Yii::$app->session->setFlash('success','添加收货地址成功');
            return $this->redirect(['address/index']);
        }
        return $this->render('index',['model'=>$model,'models'=>$models,'goodscategory'=>$goodscategory]);
    }
    //修改数据
    public function actionEdit($id){
        $model=Address::findOne(['id'=>$id]);
        $member_id=\Yii::$app->user->id;
        $models=Address::find()->all();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->is_default_address){
                foreach ($models as $v){
                    $v->is_default_address=0;
                    $v->save(false);
                }
            }
            $model->member_id=$member_id;
            $model->save();
            \Yii::$app->session->setFlash('success','修改收货地址成功');
            return $this->redirect('index.html');
        }
        return $this->render('index',['model'=>$model,'models'=>$models]);
    }
    //删除数据
    public function actionDel($id){
        $model=Address::findOne(['id'=>$id]);
        $model->delete();
        $models=Address::findAll(['member_id'=>$id]);
        return $this->render('index',['model'=>$model,'models'=>$models]);
    }
    //设置默认地址
    public function actionDefault($id){
        $models=Address::find()->all();
        foreach ($models as $v){
            $v->is_default_address=0;
            $v->save(false);
        }
        $model=Address::findOne($id);
        $model->updateAttributes(['is_default_address'=>1]);
        return $this->redirect(['address/index']);
    }

    public function actions()
    {
        $actions=parent::actions();
        $actions['get-region']=[
            'class'=>RegionAction::className(),
            'model'=>Locations::className(),
        ];
        return $actions;
    }
}
