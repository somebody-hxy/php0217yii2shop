<?php

namespace backend\controllers;

use backend\models\Admin;
use backend\models\LoginForm;
use yii\data\Pagination;
use yii\filters\AccessControl;

class AdminController extends \yii\web\Controller
{
    //登录
    public function actionLogin(){
        $model=new LoginForm();
        if($model->load(\Yii::$app->request->post())){
            if($model->validate()){
                //跳转

                return $this->redirect(['admin/index']);
            }
        }
        return $this->render('login',['model'=>$model]);
    }
    //注销
    public function actionLogout(){
        \Yii::$app->user->logout();//注销当前用户
        $model = new Admin();
        return $this->redirect(['admin/login','model'=>$model]);//跳转
    }
    //列表
    public function actionIndex()
    {
        $query=Admin::find()->where(['status'=>1]);
        $total=$query->count();
        $page=new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>2,
        ]);
        $model=$query->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',['model'=>$model,'page'=>$page]);
    }
    //添加数据
    public function actionAdd()
    {
		$model=new Admin();
        if($model->load(\Yii::$app->request->post())){
            if($model->validate()){
                //对密码进行加密
                $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password_hash);
                $model->create_at=time();
                $model->save();
                \Yii::$app->session->setFlash('success','添加管理员成功');
                return $this->redirect(['admin/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    //修改数据
    public function actionEdit($id)
    {
        $model=Admin::findOne(['id'=>$id]);
        if($model->load(\Yii::$app->request->post())){
            if($model->validate()){
                //对密码进行加密
                $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password_hash);
                $model->updated_at=time();
                $model->save();
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['admin/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    //删除数据
    public function actionDel($id)
    {
        $model=Admin::findOne(['id'=>$id]);
        $model->status=-1;
        $model->save();
        return $this->redirect('admin/index');
    }
    //验证码
    public function actions(){
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'minLength'=>4,
                'maxLength'=>4,
            ],
        ];
    }
    //权限设置,过滤器
    /**public function behaviors(){
        return [
            'acf'=>[
                'class'=>AccessControl::className(),
                'only'=>['index','del','edit','update','add'],
                'rules'=>[
                    [//未认证用户允许执行reg操作
                        'allow'=>true,//是否允许执行
                        'actions'=>['login','actions'],//指定操作
                        'roles'=>['?'],//角色？表示未认证用户
                    ],
                    [//已认证用户允许执行add操作
                        'allow'=>true,//是否允许执行x`
                        'actions'=>['del','edit','add','update','index','actions'],//指定操作
                        'roles'=>['@'],//角色？ @表示已认证用户
                    ],
                    //其他都禁止执行
                ]
            ],
        ];
    }*/
}
