<?php
/**
 * @var $this yii\web\View
 */
use yii\helpers\Html;
$this->registerCssFile('@web/style/fillin.css');

?>
<!-- 页面头部 start -->
<div class="header w990 bc mt15">
    <div class="logo w990">
        <h2 class="fl"><a href="index.html"><?=Html::img('@web/images/logo.png')?></a></h2>
        <div class="flow fr flow2">
            <ul>
                <li>1.我的购物车</li>
                <li class="cur">2.填写核对订单信息</li>
                <li>3.成功提交订单</li>
            </ul>
        </div>
    </div>
</div>
<!-- 页面头部 end -->

<div style="clear:both;"></div>

<!-- 主体部分 start   -->
<div class="fillin w990 bc mt15">

    <form action="<?=\yii\helpers\Url::to(['cart/add-order'])?>" id="subform" method="post">
        <!--跨站攻击预防-->
        <input name="_csrf-frontend" type="hidden" value="<?= Yii::$app->request->getCsrfToken() ?>">
        <input id="total" type="hidden" name="Order[total]" value=""/>
        <div class="fillin_hd">
            <h2>填写并核对订单信息</h2>
        </div>

        <div class="fillin_bd">
            <!-- 收货人信息  start-->
            <div class="address">
                <h3>收货人信息</h3>
                <div class="address_info">
                    <?php foreach($address as $v):?>
                    <p>       <!--    //( -->
                        <input type="radio" value="<?=$v->id?>" name="Order[address_id]" <?=($v->is_default_address)?'checked="checked"':''?>/>
                        <?=$v->username.' '.$v->tel.' '.$v->province->name.' '.$v->city->name.' '.
                        $v->district->name.' '.$v->detail_address?>
                    </p>
                    <?php endforeach;?>
                </div>


            </div>
            <!-- 收货人信息  end-->

            <!-- 配送方式 start -->
            <div class="delivery">
                <h3>送货方式 </h3>


                <div class="delivery_select">
                    <table>
                        <thead>
                        <tr>
                            <th class="col1">送货方式</th>
                            <th class="col2">运费</th>
                            <th class="col3">运费标准</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            $de=\frontend\models\Order::getDelivery();
                            //var_dump($de);exit;
                            foreach($de as $k=>$value):
                               // var_dump($value);exit;
                        ?>
                        <tr <?=$k==0?'class="cur" ':''?>>
                            <td class="del_price">
                                <input type="radio" class="del_id" d_price="<?=$value['price']?>" value="<?=$value['d_id']?>" name="Order[delivery_id]" <?=$k==0?'checked="checked"':''?>/>
                                <?=$value['d_name']?>
                            </td>
                            <td>￥<?=$value['price']?></td>
                            <td><?=$value['intro']?></td>
                        </tr>
                        <?php endforeach;?>
                        </tbody>
                    </table>

                </div>
            </div>
            <!-- 配送方式 end -->

            <!-- 支付方式  start-->
            <div class="pay">
                <h3>支付方式 </h3>


                <div class="pay_select">
                    <table>
                        <?php
                        $pay=\frontend\models\Order::getPay();
                        foreach($pay as $k=>$val):
                        ?>
                        <tr <?=$k==0?'class="cur" ':''?>>
                            <td class="col1">
                                <input type="radio" value="<?=$val['p_id']?>" name="Order[pay_id]" <?=$k==0?'checked="checked"':''?>/>
                                <?=$val['p_name']?></td>
                            <td class="col2"><?=$val['intro']?></td>
                        </tr>
                        <?php endforeach;?>

                    </table>

                </div>
            </div>
            <!-- 支付方式  end-->

            <!-- 发票信息 start-->
            <div class="receipt none">
                <h3>发票信息 </h3>


                <div class="receipt_select ">
                    <form action="">
                        <ul>
                            <li>
                                <label for="">发票抬头：</label>
                                <input type="radio" name="type" checked="checked" class="personal" />个人
                                <input type="radio" name="type" class="company"/>单位
                                <input type="text" class="txt company_input" disabled="disabled" />
                            </li>
                            <li>
                                <label for="">发票内容：</label>
                                <input type="radio" name="content" checked="checked" />明细
                                <input type="radio" name="content" />办公用品
                                <input type="radio" name="content" />体育休闲
                                <input type="radio" name="content" />耗材
                            </li>
                        </ul>
                    </form>

                </div>
            </div>
            <!-- 发票信息 end-->

            <!-- 商品清单 start -->
            <div class="goods">
                <h3>商品清单</h3>
                <table>
                    <thead>
                    <tr>
                        <th class="col1">商品</th>
                        <th class="col3">价格</th>
                        <th class="col4">数量</th>
                        <th class="col5">小计</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($carts as $c):?>
                    <tr>
                        <td class="col1"><a href=""><?=Html::img('http://admin.yii2shop.com'.$c['logo'])?></a>
                            <strong><a href=""><?=$c['name']?></a></strong></td>
                        <td class="col3"><?=$c['shop_price']?></td>
                        <td class="col4"> <?=$c['amount']?></td>
                        <td class="col5"><span class="price"><?=($c['shop_price']*$c['amount'])?></span></td>
                    </tr>
                    <?php endforeach;?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="5">
                            <ul>
                                <li><?php
                                    $num=0;
                                    $count=0;
                                    foreach($carts as $v){
                                        $num+=$v['shop_price']*$v['amount'];
                                        $count+=$v['amount'];
                                    }
                                    ?>
                                    <span><?php echo $count;?>件商品，总商品金额：</span>
                                    <em>￥<?php echo $num;?></em>
                                </li>
                        <!--        <li>
                                    <span>返现：</span>
                                    <em>-￥240.00</em>
                                </li>
                                <li>
                                    <span>运费：</span>
                                    <em id="yunfei"></em>
                                </li>
                                <li>
                                    <span>应付总额：</span>
                                    <em id="all_price"></em>
                                </li>
                                -->
                            </ul>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <!-- 商品清单 end -->

        </div>

        <div class="fillin_ft">
            <a id="submit" href="javascript:;" onclick="document.getElementById('subform').submit();"><span>提交订单</span></a>
            <p>应付总额：<strong id="all_price"></strong></p>
        </div>
    </form>
</div>
<!-- 主体部分 end -->
<?php

//$url = \yii\helpers\Url::to(['cart/add-order']);
//$token = Yii::$app->request->csrfToken;
$this->registerJs(new \yii\web\JsExpression(
<<<JS

    //计算总价格
        var price=0;
        $('.price').each(function(){
            price +=Number($(this).text());
        });
        //console.log(price);
        $('#all_price').text(price+10);
        $('#total').val(price+10);
        //console.log(price+10);
        $(".del_price").click(function(){
            //console.log($(this));
            var de_price = Number($(this).find('.del_id').attr('d_price'));
            var last_price = de_price + price;
            //console.log(de_price);
            $("#yunfei").val(de_price);
            //$("#yunfei").text(de_price);
            $("#all_price").text(last_price);
            //console.log(last_price);
        });

JS

));

?>