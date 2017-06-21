<?php
echo \yii\helpers\Html::a('添加菜单',['menu/add'],['class'=>'btn btn-info']);
?>
<table class="table">
    <tr>
        <th>ID</th>
        <th>菜单名称</th>
        <th>路由</th>
        <th>排序</th>
        <th>操作</th>
    </tr>
    <?php foreach($models as $model):?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->label?></td>
            <td><?=$model->url?></td>
            <td><?=$model->sort?></td>
            <td><?php
                if(Yii::$app->user->can('menu/edit')){
                    echo \yii\helpers\Html::a('修改',['menu/edit','id'=>$model->id],['class'=>'btn btn-warning btn-xs']);
                }
                ?>
                <?php
                if(Yii::$app->user->can('menu/del')){
                   echo \yii\helpers\Html::a('删除',['menu/del','id'=>$model->id],['class'=>'btn btn-danger btn-xs']);
                }
                ?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$page,
    'nextPageLabel'=>'下一页',
    'prevPageLabel'=>'上一页',
]);
?>