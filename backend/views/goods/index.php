<?php
$form=\yii\bootstrap\ActiveForm::begin([
    'action'=>\yii\helpers\Url::to(['goods/index']),
    'method'=>'get',
//    'options'=>['class'=>'form-inline'],
]);
echo'<div style="text-align: right;height:45px;">';
    echo  \yii\bootstrap\Html::textInput('name');
    echo  ' '.\yii\bootstrap\Html::textInput('sn');
    echo \yii\bootstrap\Html::submitButton('搜索',['class'=>'btn btn-primary btn-xs']);
\yii\bootstrap\ActiveForm::end();
echo'</div>';
//echo $form->field($model,'name')->textInput(['placeholder'=>'商品名称'])->label(false);
//echo $form->field($model,'sn')->textInput(['placeholder'=>'货号'])->label(false);
//echo \yii\bootstrap\Html::submitButton('搜索',['class'=>'btn btn-primary btn-xs']);
//\yii\bootstrap\ActiveForm::end();
?>


<?php echo \yii\bootstrap\Html::a('添加商品',['goods/add'],['class'=>'btn btn-info','id'=>'add']);?>
<?php echo '&nbsp;&nbsp;&nbsp;'.\yii\bootstrap\Html::a('查看商品相册',['goods-img/index'],['class'=>'btn btn-info','id'=>'in']);?>
    <div>
        <table class="table">
            <tr>
                <th>ID</th>
                <th>名称</th>
                <th>货号</th>
                <th>LOGO图片</th>
                <th>商品类别</th>
                <th>品牌类别</th>
                <th>市场价格</th>
                <th>商品价格</th>
                <th>库存</th>
                <th>是否在售</th>
                <th>排序</th>
                <th>状态</th>
                <th>时间</th>
                <th>操作</th>
            </tr>
            <?php foreach($model as $goods_list):if(($goods_list->status)>0){
                ?>
                <tr>
                    <td><?=$goods_list->id?></td>
                    <td><?=$goods_list->name?></td>
                    <td><?=$goods_list->sn?></td>
                    <td><?=\yii\bootstrap\Html::img($goods_list->logo,['width'=>60])?></td>
                    <td><?=$goods_list->goodsCategory->name?></td>
                    <td><?=$goods_list->brand->name?></td>
                    <td><?=$goods_list->market_price?></td>
                    <td><?=$goods_list->shop_price?></td>
                    <td><?=$goods_list->stock?></td>
                    <td><?=\backend\models\Goods::$sales_options[$goods_list->is_on_sale]?></td>
                    <td><?=$goods_list->sort?></td>
                    <td><?=\backend\models\Goods::$status_options[$goods_list->status]?></td>

                    <td><?=date('Y-m-d H:i:s',$goods_list->create_time)?></td>
                    <td><?=\yii\bootstrap\Html::a('修改',['goods/edit','id'=>$goods_list->id],['class'=>'btn btn-warning btn-xs'])?>
                        <?=\yii\bootstrap\Html::a('商品详情',['goods/list','id'=>$goods_list->id],['class'=>'btn btn-success btn-xs'])?>
                        <?=\yii\bootstrap\Html::a('相册',['goods/gallery','id'=>$goods_list->id],['class'=>'btn btn-info btn-xs'])?>
                        <?=\yii\bootstrap\Html::a('删除',['goods/del','id'=>$goods_list->id],['class'=>'btn btn-danger btn-xs'])?>
                    </td>
                </tr>
            <?php }?>
            <?php endforeach;?>
        </table>
    </div>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$page,
    'nextPageLabel'=>'下一页',
    'prevPageLabel'=>'上一页',
]);
?>