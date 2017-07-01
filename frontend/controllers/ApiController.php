<?php
namespace frontend\controllers;

use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\Goods;
use backend\models\GoodsCategory;
use frontend\models\Address;
use frontend\models\Member;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Response;

class ApiController extends Controller{
/**
    //测试
    public function actionGetGoodsByBrand(){

        $request=\Yii::$app->request;
        $brand_id=$request->get('brand_id');
        if($brand_id){
            $goods=Goods::find()->where(['brand_id'=>$brand_id])->asArray()->all();
            return Json::encode(['status'=>'-1','data'=>$goods]);
        }
        return Json::encode(['status'=>'-1','msg'=>'参数不正确']);
    }
 */
    public $enableCsrfValidation = false;
    public function init()
    {
        \Yii::$app->response->format =Response::FORMAT_JSON;
        parent::init();
    }
    /**
     *商品
     */
    //获取某分类下面的所有商品
    public function actionGetGoodsByCategory(){
        $request=\Yii::$app->request;
        $category_id=$request->get('category_id');
        if($category_id){
            $goods=Goods::find()->where(['goods_category_id'=>$category_id])->asArray()->all();
            return ['status'=>1,'msg'=>'','data'=>$goods];
        }
        return ['status'=>'-1','msg'=>'参数不正确'];
    }
    //获取品牌下的商品
    public function actionGetGoodsByBrand(){
        $request=\Yii::$app->request;
        $brand_id=$request->get('brand_id');
        if($brand_id){
            $goods=Goods::find()->where(['brand_id'=>$brand_id])->asArray()->all();
            return ['status'=>1,'msg'=>'','data'=>$goods];
        }
        return ['status'=>'-1','msg'=>'参数不正确'];
    }

    /**
     * @return array
     *用户部分
     */
    //用户注册
    public function actionMemberRegister(){
        $request=\Yii::$app->request;
        if($request->isPost){
            $member=new Member();
            $member->username=$request->post('username');
            $member->password=$request->post('password');
            $member->email=$request->post('email');
            $member->tel=$request->post('tel');
            if($member->validate()){
                $member->password_hash=\Yii::$app->security->generatePasswordHash($member->password);
                $member->save();
                return['status'=>'1','msg'=>'','data'=>$member->toArray()];
            }
            //验证失败
            return ['status'=>'-1','msg'=>$member->getErrors()];
        }
        return['status'=>'-1','msg'=>'使用post请求'];
    }

    //用户登录
    public function actionMemberLogin(){
        $request=\Yii::$app->request;
        if($request->isPost){
            $user=Member::findOne(['username'=>$request->post('username')]);
            if($user && \Yii::$app->security->validatePassword($request->post('password'),$user->password_hash)){
                \Yii::$app->user->login($user);
                return['status'=>'1','msg'=>'登录成功'];
            }
            return ['status'=>'-1','msg'=>'账号或密码错误'];
        }
        return ['status'=>'-1','msg'=>'使用post请求'];
    }

    //修改密码
    public function actionUpdatePwd(){
        $user=\Yii::$app->user->identity->toArray();
        $request=\Yii::$app->request;
        if($request->isPost){
            $user=Member::findOne(['username'=>$request->post('username')]);
            if($user && \Yii::$app->security->validatePassword($request->post('old_password'),$user->password_hash)){
                if($user->validate()){
                    $user->password=$request->post('password');
                    $user->password_hash=\Yii::$app->security->generatePasswordHash($user->password);
                    $user->save();
                    return['status'=>'1','msg'=>'','data'=>$user->toArray()];
                }
                //验证失败
                return ['status'=>'-1','msg'=>'旧密码不正确'];
            }
        }
        return['status'=>'-1','msg'=>'使用post请求'];
    }

    //获取当前登录用户的信息
    public function actionGetMemberIntro(){
        if(\Yii::$app->user->isGuest){
            return['status'=>'-1','msg'=>'请登录'];
        }
        return ['status'=>'1','msg'=>'','data'=>\Yii::$app->user->identity->toArray()];
    }

    /**
     * 收货地址
     */
    //添加地址
    public function actionAddAddress(){
        $member_id=\Yii::$app->user->id;
        $address=new Address();
        $request=\Yii::$app->request;
        if($request->isPost){
            $address->member_id=$member_id;
            $address->username=$request->post('username');
            $address->province_id=$request->post('province_id');
            $address->city_id=$request->post('city_id');
            $address->district_id=$request->post('district_id');
            $address->detail_address=$request->post('detail_address');
            $address->tel=$request->post('tel');
            $address->is_default_address=$request->post('is_default_address');
            if($address->validate()){
                $address->save();
                return['status'=>'1','msg'=>'','data'=>$address->toArray()];
            }
            //验证失败
            return ['status'=>'-1','msg'=>$address->getErrors()];
        }
        return['status'=>'-1','msg'=>'使用post请求'];
    }
    //修改地址
    public function actionEditAddress(){
        $member_id=\Yii::$app->user->id;
        $request=\Yii::$app->request;
        $address_id=$request->post('address_id');//地址ID
        $address=Address::findOne(['id'=>$address_id]);

        if($request->isPost){
            $address->member_id=$member_id;
            $address->username=$request->post('username');
            $address->province_id=$request->post('province_id');
            $address->city_id=$request->post('city_id');
            $address->district_id=$request->post('district_id');
            $address->detail_address=$request->post('detail_address');
            $address->tel=$request->post('tel');
            $address->is_default_address=$request->post('is_default_address');
            if($address->validate()){
                $address->save();
                return['status'=>'1','msg'=>'','data'=>$address->toArray()];
            }
            //验证失败
            return ['status'=>'-1','msg'=>$address->getErrors()];
        }
        return['status'=>'-1','msg'=>'使用post请求'];
    }
    //删除地址
    public function actionDelAddress(){
        $request=\Yii::$app->request;
        $address_id=$request->get('address_id');//地址ID
        $address=Address::findOne(['id'=>$address_id]);
        $address->delete();
        return['status'=>'1','msg'=>'删除成功'];
    }
    //显示当前用户地址列表
    public function actionListAddress(){
        $member_id=\Yii::$app->user->id;
        //var_dump($member_id);exit;
        $address=Address::findAll(['member_id'=>$member_id]);
        return['status'=>'1','msg'=>'','data'=>$address];
    }

    /**
     *商品分类
     */
    //获取所有商品分类
    public function actionGetGoodsCategory(){
        $category=GoodsCategory::find()->all();
        return['status'=>'1','msg'=>'','data'=>$category];
    }
    //获取某分类的所有子分类
    public function actionGetGoodsCategoryChildren(){
        $request=\Yii::$app->request;//还待完善
        $category_id=$request->get('category_id');
        $cate=GoodsCategory::findAll(['parent_id'=>$category_id]);
        return['status'=>'1','msg'=>'','data'=>$cate];
    }
    //获取某分类的父分类
    public function actionGetGoodsCategoryParent(){
        $request=\Yii::$app->request;
        $category_id=$request->get('category_id');
        $cate=GoodsCategory::findAll(['id'=>$category_id]);//findAll返回数组
//        var_dump($cate);exit;
        //$model=[];
        foreach($cate as $v){
           // $model[]=$v;
        }
        //var_dump($model);exit;
        $cate2=GoodsCategory::findAll(['id'=>$v->parent_id]);
        return['status'=>'1','msg'=>'','data'=>$cate2];
    }
    /**
     *文章
     */
    //获取文章分类
    public function actionArticleCategory(){
        $category=ArticleCategory::find()->all();
        return['status'=>'1','msg'=>'','data'=>$category];

    }
    //获取某分类下面的所有文章
    public function actionGetArticle(){
        $request=\Yii::$app->request;
        $category_id=$request->get('category_id');
        if($category_id){
            $article=Article::find()->where(['article_category_id'=>$category_id])->asArray()->all();
            return ['status'=>1,'msg'=>'','data'=>$article];
        }
        return ['status'=>'-1','msg'=>'参数不正确'];
    }
    //获取某文章所属分类
    public function actionGetArticleCategory(){
        $request=\Yii::$app->request;
        $article_id=$request->get('article_id');
        $art=Article::find()->where(['id'=>$article_id])->all();
        //var_dump($art);exit;
        foreach($art as $cate){
            // $model[]=$v;
        }
        $model=ArticleCategory::findAll(['id'=>$cate->article_category_id]);
        return ['status'=>1,'msg'=>'','data'=>$model];
    }

    /**
     *购物车
     */
    //添加商品到购物车
    public function actionAddCart(){
        $member_id=\Yii::$app->user->id;
        $request=\Yii::$app->request;
        if($request->isPost){
            
        }
    }
    //修改购物车某商品数量
    //删除购物车某商品
    //清空购物车
    //获取购物车所有商品
    /**
     * 订单
     */
    //获取支付方式
    //获取送货方式
    //提交订单
    //获取当前用户订单列表
    //取消订单

}