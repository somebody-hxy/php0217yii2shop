<?php

namespace frontend\controllers;

use frontend\models\Address;

class AddressController extends \yii\web\Controller
{

    public $layout = 'address';
    //添加数据
    public function actionIndex(){
        $member_id=\Yii::$app->user->id;
        $model=new Address();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
//            var_dump($model);exit;
            $model->member_id=$member_id;
            $model->save();
        }
        $models=Address::findAll(['member_id'=>$member_id]);
        return $this->render('index',['model'=>$model,'models'=>$models]);
    }
    //修改数据
    public function actionEdit($id){
        $model=Address::findOne(['id'=>$id]);
        $member_id=\Yii::$app->user->id;
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->member_id=$member_id;
            $model->save();
            return $this->redirect('index.html');
        }
        $models=Address::findAll(['member_id'=>$id]);
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
        //获取当前用户的ID
        $member_id=\Yii::$app->user->id;
        //根据当前用户的ID查询到所有的地址
        $query=Address::find()->andWhere(['member_id'=>$member_id])->all();
        foreach($query as $v){
            $model[]=$v->is_default_address;
        }
        var_dump($model);exit;
    }
}
