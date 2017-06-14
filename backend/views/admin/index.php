<?php
/* @var $this yii\web\View */
echo \yii\bootstrap\Html::a('添加管理员',['admin/add'],['class'=>'btn btn-info','id'=>'add']);
?>
<table class="table">
    <tr>
        <th>ID</th>
        <th>用户名</th>
        <th>邮箱</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach($model as $admin_list):?>
        <tr>
            <td><?=$admin_list->id?></td>
            <td><?=$admin_list->username?></td>
            <td><?=$admin_list->email?></td>
            <td><?=\backend\models\Admin::$status_options[$admin_list->status]?></td>
            <td><?=\yii\bootstrap\Html::a('修改',['admin/edit','id'=>$admin_list->id],['class'=>'btn btn-warning btn-xs'])?>
                <?=\yii\bootstrap\Html::a('删除',['admin/del','id'=>$admin_list->id],['class'=>'btn btn-danger btn-xs'])?>
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