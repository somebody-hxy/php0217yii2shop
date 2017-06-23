<?php

namespace frontend\controllers;
use frontend\models\LoginForm;
use frontend\models\Member;
use Flc\Alidayu\Client;
use Flc\Alidayu\App;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;
use Flc\Alidayu\Requests\IRequest;
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
        //var_dump($model->getErrors());
        return $this->render('register',['model'=>$model]);
    }
    //登录
    public function actionLogin(){
        $model=new LoginForm();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            \Yii::$app->session->setFlash('success','登录成功');
            return $this->redirect(['index/index']);
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

    public function actionTest(){
        // 配置信息
        $config = [
            'app_key'    => '24480028',
            'app_secret' => '4bfb70608b2cc23025a1b37f1aa84a68',
            // 'sandbox'    => true,  // 是否为沙箱环境，默认false
        ];


// 使用方法一
        $client = new Client(new App($config));
        $req    = new AlibabaAliqinFcSmsNumSend;
        $code=rand(100000, 999999);
        $req->setRecNum('13312311231')
            ->setSmsParam([
                'code' => $code
            ])
            ->setSmsFreeSignName('短信验证码')
            ->setSmsTemplateCode('SMS_71835215');

        $resp = $client->execute($req);
        var_dump($resp);
        var_dump($code);
// 使用方法二
/**        Client::configure($config);  // 全局定义配置（定义一次即可，无需重复定义）

        $resp = Client::request('alibaba.aliqin.fc.sms.num.send', function (IRequest $req) {
            $req->setRecNum('13312311231')
                ->setSmsParam([
                    'number' => rand(100000, 999999)
                ])
                ->setSmsFreeSignName('叶子坑')
                ->setSmsTemplateCode('SMS_71835215');
        });
*/
// 返回结果
//        print_r($resp);
//        print_r($resp->result->model);
    }

}
