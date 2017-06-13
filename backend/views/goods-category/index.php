<?php echo \yii\bootstrap\Html::a('添加商品分类',['goods-category/add'],['class'=>'btn btn-info','id'=>'add']);?>
<div>
    <table class="table">
        <tr>
            <th>ID</th>
            <th>名称</th>
<!--            <th>分类名称</th>-->

            <th>简介</th>
            <th>操作</th>
        </tr>
        <?php foreach($model as $cate_list):?>
            <tr>
                <td><?=$cate_list->id?></td>
                <td><?=$cate_list->name?></td>
                <td><?=$cate_list->intro?></td>
                <td><?=\yii\bootstrap\Html::a('修改',['goods-category/edit','id'=>$cate_list->id],['class'=>'btn btn-warning btn-xs'])?>
                    <?=\yii\bootstrap\Html::a('删除',['goods-category/del','id'=>$cate_list->id],['class'=>'btn btn-danger btn-xs'])?>
                </td>
            </tr>
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