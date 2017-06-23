<?php
use yii\helpers\Html;
?>
<!-- 顶部导航 end -->
<div style="clear:both;"></div>

<!-- 头部 start -->
<div class="header w1210 bc mt15">
<!-- 头部上半部分 start 包括 logo、搜索、用户中心和购物车结算 -->
<div class="logo w1210">
    <h1 class="fl"><a href="index.html"><?=Html::img('@web/images/logo.png')?></a></h1>
    <!-- 头部搜索 start -->
    <div class="search fl">
        <div class="search_form">
            <div class="form_left fl"></div>
            <form action="" name="serarch" method="get" class="fl">
                <input type="text" class="txt" value="请输入商品关键字" /><input type="submit" class="btn" value="搜索" />
            </form>
            <div class="form_right fl"></div>
        </div>

        <div style="clear:both;"></div>

        <div class="hot_search">
            <strong>热门搜索:</strong>
            <a href="">D-Link无线路由</a>
            <a href="">休闲男鞋</a>
            <a href="">TCL空调</a>
            <a href="">耐克篮球鞋</a>
        </div>
    </div>
    <!-- 头部搜索 end -->

    <!-- 用户中心 start-->
    <div class="user fl">
        <dl>
            <dt>
                <em></em>
                <a href="">用户中心</a>
                <b></b>
            </dt>
            <dd>
                <div class="prompt">
                    <!--                        您好，请<a href="">登录</a>-->
                    <?php if(Yii::$app->user->isGuest):?>
                        您好，请[<?=Html::a('登录',['user/login'])?>]
                    <?php else:?>
                        <?=Yii::$app->user->identity->username?>
                        [<?=Html::a('注销',['user/logout'])?>]
                    <?php endif;?>
                </div>
                <div class="uclist mt10">
                    <ul class="list1 fl">
                        <li><a href="">用户信息></a></li>
                        <li><a href="">我的订单></a></li>
                        <li><a href="">收货地址></a></li>
                        <li><a href="">我的收藏></a></li>
                    </ul>

                    <ul class="fl">
                        <li><a href="">我的留言></a></li>
                        <li><a href="">我的红包></a></li>
                        <li><a href="">我的评论></a></li>
                        <li><a href="">资金管理></a></li>
                    </ul>

                </div>
                <div style="clear:both;"></div>
                <div class="viewlist mt10">
                    <h3>最近浏览的商品：</h3>
                    <ul>
                        <li><a href=""><?=Html::img('@web/images/view_list1.jpg')?></a></li>
                        <li><a href=""><?=Html::img('@web/images/view_list2.jpg')?></a></li>
                        <li><a href=""><?=Html::img('@web/images/view_list3.jpg')?></a></li>
                    </ul>
                </div>
            </dd>
        </dl>
    </div>
    <!-- 用户中心 end-->

    <!-- 购物车 start -->
    <div class="cart fl">
        <dl>
            <dt>
                <a href="">去购物车结算</a>
                <b></b>
            </dt>
            <dd>
                <div class="prompt">
                    购物车中还没有商品，赶紧选购吧！
                </div>
            </dd>
        </dl>
    </div>
    <!-- 购物车 end -->
</div>
<!-- 头部上半部分 end -->

<div style="clear:both;"></div>
<!-- 导航条部分 start -->
<div class="nav w1210 bc mt10">
<!--  商品分类部分 start-->
<div class="category fl cat1"> <!-- 非首页，需要添加cat1类 -->
<div class="cat_hd">  <!-- 注意，首页在此div上只需要添加cat_hd类，非首页，默认收缩分类时添加上off类，鼠标滑过时展开菜单则将off类换成on类 -->
    <h2>全部商品分类</h2>
    <em></em>
</div>

<div class="cat_bd none">

    <?php foreach($goodscategory as $k=>$category):?>
        <div class="cat <?=$k==0?"item1":""?>">
            <h3><?=\yii\helpers\Html::a($category->name,['index/list','cate_id'=>$category->id])?>
                <b></b>
            </h3>
            <div class="cat_detail">
                <?php foreach($category->children as $k2=>$value):?>
                    <dl <?=$k2==0?'class="dl_1st"':''?>>
                        <dt><?=\yii\helpers\Html::a($value->name,['index/list','cate_id'=>$value->id])?></dt>
                        <?php foreach($value->children as $v):?>
                            <dd>
                                <?=\yii\helpers\Html::a($v->name,['index/list','cate_id'=>$v->id])?>
                            </dd>
                        <?php endforeach;?>
                    </dl>
                <?php endforeach;?>
            </div>
        </div>
    <?php endforeach;?>
</div>
</div>
<!--  商品分类部分 end-->

<div class="navitems fl">
    <ul class="fl">
        <li class="current"><a href="">首页</a></li>
        <li><a href="">电脑频道</a></li>
        <li><a href="">家用电器</a></li>
        <li><a href="">品牌大全</a></li>
        <li><a href="">团购</a></li>
        <li><a href="">积分商城</a></li>
        <li><a href="">夺宝奇兵</a></li>
    </ul>
    <div class="right_corner fl"></div>
</div>
</div>
<!-- 导航条部分 end -->
</div>
<!-- 头部 end-->

<div style="clear:both;"></div>

<!-- 页面主体 start -->
<div class="main w1210 bc mt10">
    <div class="crumb w1210">
        <h2><strong>我的XX </strong><span>> 我的订单</span></h2>
    </div>

    <!-- 左侧导航菜单 start -->
    <div class="menu fl">
        <h3>我的XX</h3>
        <div class="menu_wrap">
            <dl>
                <dt>订单中心 <b></b></dt>
                <dd><b>.</b><a href="">我的订单</a></dd>
                <dd><b>.</b><a href="">我的关注</a></dd>
                <dd><b>.</b><a href="">浏览历史</a></dd>
                <dd><b>.</b><a href="">我的团购</a></dd>
            </dl>

            <dl>
                <dt>账户中心 <b></b></dt>
                <dd class="cur"><b>.</b><a href="">账户信息</a></dd>
                <dd><b>.</b><a href="">账户余额</a></dd>
                <dd><b>.</b><a href="">消费记录</a></dd>
                <dd><b>.</b><a href="">我的积分</a></dd>
                <dd><b>.</b><a href="">收货地址</a></dd>
            </dl>

            <dl>
                <dt>订单中心 <b></b></dt>
                <dd><b>.</b><a href="">返修/退换货</a></dd>
                <dd><b>.</b><a href="">取消订单记录</a></dd>
                <dd><b>.</b><a href="">我的投诉</a></dd>
            </dl>
        </div>
    </div>
    <!-- 左侧导航菜单 end -->


    <!-- 右侧内容区域 start -->
<div class="content fl ml10">
    <div class="address_hd" >
         <h3>收货地址薄</h3>
<!--        <dl>-->
<!--            <dt>1.许坤 北京市 昌平区 仙人跳区 仙人跳大街 17002810530 </dt>-->
<!--            <dd>-->
<!--                <a href="">修改</a>-->
<!--                <a href="">删除</a>-->
<!--                <a href="">设为默认地址</a>-->
<!--            </dd>-->
<!--        </dl>-->
        <!-- 最后一个dl 加类last -->
<!--          <dl class="last">-->
<!--           <dt>2.许坤 四川省 成都市 高新区 仙人跳大街 17002810530 </dt>-->
<!--            <dd>-->
<!--                <a href="">修改</a>-->
<!--                <a href="">删除</a>-->
<!--                <a href="">设为默认地址</a>-->
<!--            </dd>-->
<!--        </dl>-->
        <?php
        foreach($models as $model2):?>
            <dl>
                <dt><?=$model2->id.' '.$model2->username.' '.$model2->province->name.' '.$model2->city->name.' '.
                        $model2->district->name.' '.$model2->detail_address.' '.$model2->tel?></dt>
                <dd>
                    <?=Html::a('修改',['address/edit','id'=>$model2->id])?>
                    <?=Html::a('删除',['address/edit','id'=>$model2->id])?>
                    <?=Html::a('设为默认地址',['address/default','id'=>$model2->id])?>
                </dd>
            </dl>
        <?php endforeach;?>
    </div>

    <div class="address_bd mt10">
        <h4>新增收货地址</h4>
        <?php
        $form=\yii\widgets\ActiveForm::begin([
            'fieldConfig'=>[
                'options'=>[
                    'tag'=>'li',
                ],
                'errorOptions'=>[
                    'tag'=>'p',
                ],
            ],
//            'options'=>['class'=>'form-inline'],
        ]);
        echo '<ul>';
        echo $form->field($model,'username')->textInput(['class'=>'txt']);
//        echo $form->field($model,'province')->textInput(['placeholder'=>'省','class'=>'txt']);
//        echo $form->field($model,'city')->textInput(['placeholder'=>'市','class'=>'txt']);
//        echo $form->field($model,'area')->textInput(['placeholder'=>'县区','class'=>'txt']);

        $url=\yii\helpers\Url::toRoute(['get-region']);

        echo $form->field($model, 'province_id')->widget(\chenkby\region\Region::className(),[
            'model'=>$model,
            'url'=>$url,
            'province'=>[
                'attribute'=>'province_id',
                'items'=>\frontend\models\Locations::getRegion(),
                'options'=>['class'=>'form-control form-control-inline','prompt'=>'选择省份']
            ],
            'city'=>[
                'attribute'=>'city_id',
                'items'=>\frontend\models\Locations::getRegion($model['province_id']),
                'options'=>['class'=>'form-control form-control-inline','prompt'=>'选择城市']
            ],
            'district'=>[
                'attribute'=>'district_id',
                'items'=>\frontend\models\Locations::getRegion($model['city_id']),
                'options'=>['class'=>'form-control form-control-inline','prompt'=>'选择县/区']
            ]
        ]);


        echo $form->field($model,'detail_address')->textInput(['class'=>'txt address']);
        echo $form->field($model,'tel')->textInput(['class'=>'txt']);
        echo '<li>
                <label for="">&nbsp;</label>'.
                $form->field($model,'is_default_address')->checkbox()
              .'</li>';
        //echo $form->field($model,'is_default_address')->checkbox();
        echo '<br/>';
        echo '<li>
                    <label for="">&nbsp;</label>
                    <input type="submit" name="" class="btn" value="保存" />
                </li>';
        echo '</ul>';
        \yii\widgets\ActiveForm::end();
        ?>

    </div>

</div>
<!-- 右侧内容区域 end -->
</div>
<!-- 页面主体 end-->
