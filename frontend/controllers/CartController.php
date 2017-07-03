<?php

namespace frontend\controllers;

use backend\models\Goods;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Locations;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\db\Exception;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;

class CartController extends \yii\web\Controller
{
    public $layout='cart';
    public function actionIndex()
    {
        return $this->render('index');
    }
    //添加到购物车
    public function actionAdd(){

        $member_id=\Yii::$app->user->id;
        $goods_id=\Yii::$app->request->post('goods_id');
        $amount=\Yii::$app->request->post('amount');
        $goods=Goods::findOne(['id'=>$goods_id]);

        //判断商品是否存在
        if($goods==null){
            throw new NotFoundHttpException('商品不存在');
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
                //\Yii::$app->session->setFlash('success','商品已加入购物车');
            }
        }
        return $this->redirect(['cart/flow1']);
    }
    //购物车页面
    public function actionFlow1()
    {
        if(\Yii::$app->user->isGuest) {
            //取出cookie中的商品id和数量
            $cookies = \Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');
            if ($cookie == null) {
                //cookie中没有购物车数据
                $cart = [];
            } else {
                $cart = unserialize($cookie->value);
            }
            $models = [];
            foreach ($cart as $good_id => $amount) {
                $goods = Goods::findOne(['id' => $good_id])->attributes;
                $goods['amount'] = $amount;
                $models[] = $goods;
            }
            //var_dump($models);exit;
        }else{
            //从数据库获取购物车数据
            $member_id=\Yii::$app->user->id;
            $cart=Cart::findAll(['member_id'=>$member_id]);
            //判断商品是否存在
            if($cart==null){
                throw new NotFoundHttpException('商品不存在');
            }
            $models = [];
//            foreach ($cart as $good_id => $amount) {
            foreach ($cart as $car) {
//                $goods = Goods::findOne(['id' => $good_id])->attributes;
                $goods = Goods::findOne(['id' => $car])->attributes;
                $goods['amount'] = $car->amount;
                $models[] = $goods;
            }
        }
        return $this->render('flow1', ['models' =>$models]);
    }
    //修改购物车
    public function actionUpdateFlow1()
    {
        $goods_id = \Yii::$app->request->post('goods_id');
        $amount = \Yii::$app->request->post('amount');
        $goods = Goods::findOne(['id'=>$goods_id]);
        if($goods==null){
            throw new NotFoundHttpException('商品不存在');
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
            $member_id=\Yii::$app->user->id;
            $model=Cart::findOne(['member_id'=>$member_id,'goods_id'=>$goods_id]);
            //判断商品是否存在
            if($model==null){
                throw new NotFoundHttpException('商品不存在');
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
                //$goods = Goods::findOne(['id' => $good_id]);
                $goods['amount'] = $car->amount;
                $models[] = $goods;
            }
        }
    }


    //订单页面
    public function actionFlow2(){
        $member_id=\Yii::$app->user->id;
        $address=Address::findAll(['member_id'=>$member_id]);
        if($address==null){
            //echo '请填写收货地址';
            return $this->redirect(['user/login']);
        }
        $cart=Cart::findAll(['member_id'=>$member_id]);
        //判断商品是否存在
        if($cart==null){
            throw new NotFoundHttpException('商品不存在');
        }
        $carts = [];
        foreach ($cart as $car) {
            $goods = Goods::findOne(['id' => $car])->attributes;
            $goods['amount'] = $car->amount;
            $carts[] = $goods;
        }
        return $this->render('flow2',['address'=>$address,'carts'=>$carts]);
    }
    //将订单信息保存到数据库
    public function actionAddOrder(){
        $model=new Order();
        $member_id=\Yii::$app->user->id;
        if($model->load(\Yii::$app->request->post()) && $model->validate()){

            $form=\Yii::$app->request->post();
            //var_dump($form['Order']);exit;
            $add_id=$form['Order']['address_id'];
            $del_id=$form['Order']['delivery_id'];
            $pay_id=$form['Order']['pay_id'];
            $total=$form['Order']['total'];

            $address=Address::findOne(['id'=>$add_id,'member_id'=>$member_id]);
            if($address == null){
                throw new NotFoundHttpException('地址不存在');
            }
            //查询省、市、县
            $province=Locations::findOne(['id'=>$address->province_id]);
            $city=Locations::findOne(['id'=>$address->city_id]);
            $area=Locations::findOne(['id'=>$address->district_id]);

            //保存省、市、县的名字到数据库
            $model->province=$province->name;
            $model->city=$city->name;
            $model->area=$area->name;
            $model->name=$address->username;
            $model->address=$address->detail_address;
            $model->tel=$address->tel;
            $model->member_id=$member_id;
            //保存送货方式
            $model->delivery_id=$del_id;
            $model->delivery_name=Order::$deliveries[$model->delivery_id]['name'];
            $model->delivery_price=Order::$deliveries[$model->delivery_id]['price'];
            //var_dump($model->delivery_name);exit;

            //保存支付方式
            $model->payment_id=$pay_id;
            $model->payment_name=Order::$paies[$model->payment_id]['name'];
            //总金额
            $model->total=$total; //待完成
            $model->status=1;
            $model->create_time=time();
            //开启事务
            $transaction=\Yii::$app->db->beginTransaction();
            try{
                //保存到数据库
                $model->save();

                //保存数据到order_goods表

                //购物车
                $carts=Cart::findAll(['member_id'=>$member_id]);
                //var_dump($carts);exit;
                foreach($carts as $cart){
                    $goods = Goods::findOne(['id'=>$cart->goods_id,'status'=>1]);
                    if($goods==null){
                        //商品不存在
                       // throw new Exception($goods->name.'商品已售完');
                        throw new Exception('商品已售完');
                    }
                    if($goods->stock < $cart->amount){
                        //库存不足
                        //throw new Exception($goods->name.'商品库存不足');
                        throw new Exception('商品库存不足');
                    }
                    $order_goods = new OrderGoods();
                    //获取订单的ID
                    $order_goods->order_id= $model->id;
                    $order_goods->goods_id= $cart->goods_id;
                    $order_goods->goods_name= $goods->name;
                    $order_goods->logo= $goods->logo;
                    $order_goods->price= $goods->shop_price;
                    $order_goods->goods_num=$cart->amount;
                    $order_goods->total = ($order_goods->price)*($order_goods->goods_num);
                    $order_goods->save();
                    //扣库存 //扣减该商品库存
                    $goods->stock -= $cart->amount;
                    $goods->save();
                    //清空购物车数据
                    Cart::deleteAll(['member_id'=>$member_id]);

                }
                //提交
                $transaction->commit();
                return $this->redirect(['crat/flow3']);
            }catch (Exception $e){
                //事务回滚
                $transaction->rollBack();
            }
        }
    }


    //支付成功页面
    public function actionFlow3(){

        return $this->render('flow3');
    }
}
