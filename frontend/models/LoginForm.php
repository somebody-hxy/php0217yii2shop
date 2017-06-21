<?php
namespace frontend\models;

use yii\base\Model;

class LoginForm extends Model{
    public $rememberMe;//保存登录
    //用户名
    public $username;
    //密码
    public $password;
    //验证码
    public $code;

    public function rules(){
        return[
            [['username','password'],'required'],
            ['rememberMe','safe'],
            ['password','validaPassword']
        ];
    }

    public function attributeLabels(){
        return[
            'username'=>'用户名：',
            'password'=>'密码：',
            'code'=>'验证码：',
            'rememberMe'=>'保存登录',
        ];
    }

    //自定义验证方法
    public function validaPassword(){
        $member=Member::findOne(['username'=>$this->username]);
        if($member){
            //用户存在验证密码
            if(!\Yii::$app->security->validatePassword($this->password,$member->password_hash)){
                //账号密码正确就登录
                $this->addError('password','密码不正确');

            }else{
                $member->last_login_time=time();
//              $member->last_login_ip=$_SERVER["REMOTE_ADDR"];
                $member->last_login_ip=ip2long(\Yii::$app->request->getUserIP());
                $member->save(false);
                $duration = $this->rememberMe?7*24*3600:0;
                \Yii::$app->user->login($member,$duration);
                return true;
            }
        }else{
            //账号不存在就添加错误
            $this->addError('username','账号不正确');
        }
        return false;
    }
}