<?php

namespace frontend\controllers;
use frontend\models\LoginForm;
use frontend\models\Member;

class UserController extends \yii\web\Controller
{
    public $layout = 'login';
    //注册
    public function actionRegister(){
        $model=new Member();
        if($model->load(\Yii::$app->request->post())){
            if($model->validate()){
                $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password);
//              var_dump($model);exit;
                $model->save(false);
                \Yii::$app->session->setFlash('success','注册成功');
                return $this->redirect(['user/login']);
            }
        }
        var_dump($model->getErrors());
        return $this->render('register',['model'=>$model]);
    }
    //登录
    public function actionLogin(){
        $model=new LoginForm();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            \Yii::$app->session->setFlash('success','登录成功');
            return $this->redirect(['user/index']);
        }
        return $this->render('login',['model'=>$model]);
    }

    public function actionLogout()
    {
        \Yii::$app->user->logout();
        return $this->redirect(['user/login']);
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

}
