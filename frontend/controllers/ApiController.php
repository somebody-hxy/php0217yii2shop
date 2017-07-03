<?php
namespace frontend\controllers;

use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\Goods;
use backend\models\GoodsCategory;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Member;
use frontend\models\Order;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Cookie;
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
        $cate=GoodsCategory::findOne(['id'=>$category_id]);
        if($cate==null){
            return['status'=>'11','msg'=>'该商品不存在'];
        }
        $tree=$cate->tree;
        $lft=$cate->lft;
        $rgt=$cate->rgt;
        $model=GoodsCategory::find()->where(['tree'=>$tree])->andWhere(['>','lft',$lft])->andWhere(['<','rgt',$rgt])->all();
        return['status'=>'1','msg'=>'','data'=>$model];
    }
    //获取某分类的父分类
    public function actionGetGoodsCategoryParent(){
        $request=\Yii::$app->request;
        $category_id=$request->get('category_id');
        $cate=GoodsCategory::findOne(['id'=>$category_id]);//findAll返回数组
//        var_dump($cate);exit;
        $cate2=GoodsCategory::findOne(['id'=>$cate->parent_id]);
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
        $art=Article::findOne(['id'=>$article_id]);
        $model=ArticleCategory::findAll(['id'=>$art->article_category_id]);
        return ['status'=>1,'msg'=>'','data'=>$model];
    }

    /**
     *购物车
     */
    //添加商品到购物车
    public function actionAddCart(){
        $member_id=\Yii::$app->user->id;
        $request=\Yii::$app->request;
        $goods_id=$request->post('goods_id');
        $amount=$request->post('amount');
        $goods=Goods::findOne(['id'=>$goods_id]);

        //判断商品是否存在
        if($goods==null){
            return ['status'=>-1,'msg'=>'商品不存在'];
        }
        //判断用户是否登录
        if(\Yii::$app->user->isGuest){
            //未登录
            //先获取cookie中的购物车数据
            $cookies=\Yii::$app->request->cookies;
            $cookie=$cookies->get('cart');
            if($cookie==null){
                //cookie中没有购物车数据
                $cart=[];
            }else{
                $cart=unserialize($cookie->value);
            }
            //将商品ID和数量保存到cookie
            $cookies=\Yii::$app->response->cookies;
            //检查购物车中是否有该商品，如果有，数量累加
            if(key_exists($goods->id,$cart)){
                $cart[$goods_id]+=$amount;
            }else{
                $cart[$goods_id]=$amount;
            }
            $cookie=new Cookie([
                'name'=>'cart','value'=>serialize($cart)
            ]);
            $cookies->add($cookie);
        }else{
            //已登录，将cookie中数据合并到数据库
            //先获取cookie中的购物车数据
            $cookies=\Yii::$app->request->cookies;
            $cookie=$cookies->get('cart');
            if($cookie==null){
                //cookie中没有购物车数据
                $carts=[];
            }else{
                $carts=unserialize($cookie->value);
                //遍历cookie并保存到数据库
                foreach($carts as $key=>$good){
                    $model2=Cart::findOne(['member_id'=>$member_id,'goods_id'=>$key]);
                    if($model2){
                        $model2->amount +=$amount;
                        $model2->save();
                    }else{
                        $cart=new Cart();
                        $cart->member_id=$member_id;
                        $cart->goods_id=$key;
                        $cart->amount=$amount;
                        $cart->save();
                    }
                }
            }
            //已登录，操作数据库
            $model=Cart::findOne(['member_id'=>$member_id,'goods_id'=>$goods_id]);
            if($model){
                $model->amount +=$amount;
                $model->save();
            }else{
                $cart=new Cart();
                $cart->member_id=$member_id;
                $cart->goods_id=$goods_id;
                $cart->amount=$amount;
                $cart->save();
            }
            return ['status'=>1,'msg'=>'','data'=>$cart];
        }
        return ['status'=>-1,'msg'=>'添加失败'];
    }
    //修改购物车某商品数量
    public function actionEditCart(){
        $member_id=\Yii::$app->user->id;
        $request=\Yii::$app->request;
        $goods_id=$request->post('goods_id');
        $amount=$request->post('amount');
        $goods=Goods::findOne(['id'=>$goods_id]);

        //判断商品是否存在
        if($goods==null){
            return ['status'=>-1,'msg'=>'商品不存在'];
        }
        if(\Yii::$app->user->isGuest){
            //未登录
            //先获取cookie中的购物车数据
            $cookies = \Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');
            if($cookie == null){
                //cookie中没有购物车数据
                $cart = [];
            }else{
                $cart = unserialize($cookie->value);
                //$cart = [2=>10];
            }
            //将商品id和数量存到cookie
            $cookies = \Yii::$app->response->cookies;
            if($amount){
                $cart[$goods_id] = $amount;
            }else{
                if(key_exists($goods['id'],$cart)) unset($cart[$goods_id]);
            }
            $cookie = new Cookie([
                'name'=>'cart','value'=>serialize($cart)
            ]);
            $cookies->add($cookie);
        }else{
            //已登录  修改数据库里面的购物车数据
            $model=Cart::findOne(['member_id'=>$member_id,'goods_id'=>$goods_id]);
            //判断商品是否存在
            if($model==null){
                return ['status'=>-1,'msg'=>'商品不存在'];
            }
            if(($amount)>0){
                //if(($model->amount)>0){
                $model->amount=$amount;
                $model->save();
            }else{
                $model->delete();
            }
            $models = [];
            $cart=Cart::findAll(['member_id'=>$member_id]);
            foreach ($cart as $car) {
                $goods = Goods::findOne(['id' => $car])->attributes;
                $goods['amount'] = $car->amount;
                $models[] = $goods;
            }
            return ['status'=>1,'msg'=>'','data'=>$model];
        }
        return ['status'=>-1,'msg'=>'修改失败'];
    }
    //删除购物车某商品
    public function actionDelCart(){
        $member_id=\Yii::$app->user->id;
        $request=\Yii::$app->request;
        //获取商品ID
        $goods_id=$request->post('goods_id');
        $cart=Cart::findOne(['member_id'=>$member_id,'goods_id'=>$goods_id]);
        if($cart==null){
            return ['status'=>'-1','msg'=>'商品不存在'];
        }
        $cart->delete();
        return ['status'=>'1','msg'=>'删除成功'];
    }
    //清空购物车
    public function actionClearnCart(){
        $member_id=\Yii::$app->user->id;
        Cart::deleteAll(['member_id'=>$member_id]);
        return ['status'=>'1','msg'=>'清空购物车成功'];
    }
    //获取购物车所有商品
    public function actionListCart(){
        $member_id=\Yii::$app->user->id;
        $cart=Cart::findAll(['member_id'=>$member_id]);
        return['status'=>'1','msg'=>'','data'=>$cart];
    }
    /**
     * 订单
     */
    //获取支付方式
    public function actionGetPayment(){
        $request=\Yii::$app->request;
        $pay_id=$request->get('payment_id');
//        $pay_id=$request->post('payment_id');
        $payment_name=Order::$paies[$pay_id]['name'];
//        var_dump($payment_name);exit;
        $model=[$pay_id,$payment_name];
        return ['status'=>'1','msg'=>'','data'=>$model];
    }
    //获取送货方式
    public function actionGetDelivery(){
        $request=\Yii::$app->request;
        $del_id=$request->get('delivery_id');
//        $del_id=$request->post('deliveryt_id');
        $delivery_name=Order::$deliveries[$del_id]['name'];
        $delivery_price=Order::$deliveries[$del_id]['price'];
//        var_dump($delivery_name,$delivery_price);exit;
        $model=[$del_id,$delivery_name,$delivery_price];
        return ['status'=>'1','msg'=>'','data'=>$model];
    }
    //提交订单
    public function actionSubmitOrder(){
        $member_id=\Yii::$app->user->id;
        $request=\Yii::$app->request;
        $order=new Order();
        if($request->isPost){
            $order->member_id=$member_id;
            $order->name=$request->post('name');
            $order->province=$request->post('province');
            $order->city=$request->post('city');
            $order->area=$request->post('area');
            $order->address=$request->post('address');
            $order->tel=$request->post('tel');
            $order->delivery_id=$request->post('delivery_id');
            $order->delivery_name=$request->post('delivery_name');
            $order->delivery_price=$request->post('delivery_price');
            $order->payment_id=$request->post('payment_id');
            $order->payment_name=$request->post('payment_name');
            $order->total=$request->post('total');
            $order->status=1;
            $order->create_time=time();
            if($order->validate()){
                $order->save();
                //return['status'=>'1','msg'=>'','data'=>$order->toArray()];


            }
            //验证失败
            return ['status'=>'-1','msg'=>$order->getErrors()];
        }
        return ['status'=>'-1','msg'=>'使用post请求'];
    }
    //获取当前用户订单列表
    public function actionListOrder(){
        $member_id=\Yii::$app->user->id;
        $order=Order::findAll(['member_id'=>$member_id]);
        return ['status'=>'1','msg'=>'','data'=>$order];
    }
    //取消订单
    public function actionCancelOrder(){
        //将订单的状态改为0
        $member_id=\Yii::$app->user->id;
        $order=Order::findAll(['member_id'=>$member_id]);
        return ['status'=>'1','msg'=>'','data'=>$order];
    }

    //api高级
}