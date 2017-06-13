<?php echo \yii\bootstrap\Html::a('添加商品图片',['goods-img/add'],['class'=>'btn btn-info','id'=>'add']);?>
<div class="model">
    <table class="table">
        <tr>
            <th>ID</th>
            <th>商品名称</th>
            <th>logo</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        <?php
        foreach($model as $img_list):
            if(($img_list->goods_status)>0){
            ?>
            <tr>
                <td><?=$img_list->id?></td>
                <td><?=$img_list->goods->name?></td>
                <td><?=\yii\bootstrap\Html::img($img_list->goods_logo,['width'=>80])?></td>
                <td><?=($img_list->goods_status)?'正常':'隐藏'?></td>
                <td><?=\yii\bootstrap\Html::a('修改',['goods-img/edit','id'=>$img_list->id],['class'=>'btn btn-warning btn-xs'])?>
                    <?=\yii\bootstrap\Html::a('删除',['goods-img/del','id'=>$img_list->id],['class'=>'btn btn-danger btn-xs'])?>
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
