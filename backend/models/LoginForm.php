<?php
namespace backend\models;

use backend\models\Admin;
use yii\base\Model;

class LoginForm extends Model{
    //用户名
    public $username;
    //密码
    public $password_hash;
    //验证码
    public $code;
    //记住登录
    public $remember_me;

    public function rules(){
        return [
            [['username','password_hash'],'required'],
            //validateUsername自定义的验证方法
            ['code','captcha','captchaAction'=>'admin/captcha'],
            ['username','validateUsername'],
        ];
    }

    public function attributeLabels(){
        return[
            'username'=>'用户名',
            'password_hash'=>'密码',
            'code'=>'验证码',
            'remember_me'=>'记住密码',
        ];
    }
    //自定义验证方法
    public function validateUsername(){
        $admin=Admin::findOne(['username'=>$this->username]);
        if($admin){
            //用户存在验证密码
            if(\Yii::$app->security->validatePassword($this->password_hash,$admin->password_hash)){
                //账号密码正确就登录
                \Yii::$app->user->login($admin);
//                \Yii::$app->user->identity->last_login_time=time();
//                \Yii::$app->user->identity->last_login_ip=$_SERVER["REMOTE_ADDR"];
                $admin->last_login_time=time();
                $admin->last_login_ip=$_SERVER["REMOTE_ADDR"];
                $admin->save(false);
            }else{
                $this->addError('password_hash','密码不正确');
            }
        }else{
            //账号不存在就添加错误
            $this->addError('username','账号不正确');
        }
    }
}