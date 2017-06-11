<?php echo \yii\bootstrap\Html::a('添加文章分类',['article-category/add'],['class'=>'btn btn-info','id'=>'add']);?>
<div>
    <table class="table">
        <tr>
            <th>ID</th>
            <th>名称</th>
            <th>简介</th>
            <th>排序</th>
            <th>状态</th>
            <th>类型</th>
            <th>操作</th>
        </tr>
        <?php foreach($model as $cate_list):if(($cate_list->status)>-1){?>
            <tr>
                <td><?=$cate_list->id?></td>
                <td><?=$cate_list->name?></td>
                <td><?=$cate_list->intro?></td>
                <td><?=$cate_list->sort?></td>
                <td><?=($cate_list->status)?'正常':'隐藏'?></td>
                <td><?=($cate_list->is_help)?'是':'否'?></td>
                <td><?=\yii\bootstrap\Html::a('修改',['article-category/edit','id'=>$cate_list->id],['class'=>'btn btn-warning btn-xs'])?>
                    <?=\yii\bootstrap\Html::a('删除',['article-category/del','id'=>$cate_list->id],['class'=>'btn btn-danger btn-xs'])?>
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