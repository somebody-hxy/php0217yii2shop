<?php echo \yii\bootstrap\Html::a('添加品牌',['brand/add'],['class'=>'btn btn-info','id'=>'add']);?>
<div class="model">
    <table class="table">
        <tr>
            <th>ID</th>
            <th>名称</th>
            <th>logo</th>
            <th>简介</th>
            <th>排序</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        <?php
        foreach($model as $brand_info):
            //判断status的值
            if(($brand_info->status)>-1){
            ?>
            <tr>
                <td><?=$brand_info->id?></td>
                <td><?=$brand_info->name?></td>
                <td><?=\yii\bootstrap\Html::img($brand_info->logo,['width'=>80])?></td>
                <td><?=$brand_info->intro?></td>
                <td><?=$brand_info->sort?></td>
                <td><?=($brand_info->status)?'正常':'隐藏'?></td>
                <td><?=\yii\bootstrap\Html::a('修改',['brand/edit','id'=>$brand_info->id],['class'=>'btn btn-warning btn-xs'])?>
                    <?=\yii\bootstrap\Html::a('删除',['brand/del','id'=>$brand_info->id],['class'=>'btn btn-danger btn-xs'])?>
                </td>
            </tr>
        <?php };?>
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
