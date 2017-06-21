<?php echo \yii\bootstrap\Html::a('添加角色',['add-role'],['class'=>'btn btn-info'])?>
<table class="table">
    <tr>
        <th>角色名称</th>
        <th>角色描述</th>
        <th>角色权限</th>
        <th>操作</th>
    </tr>
    <?php foreach($models as $model):?>
        <tr>
            <td><?=$model->name?></td>
            <td><?=$model->description?></td>
            <td><?php
                $per=Yii::$app->authManager->getPermissionsByRole($model->name);
                foreach($per as $permission){
                    echo $permission->description;
                    echo ' ';
                }
                ?></td>
            <td>
                <?=\yii\bootstrap\Html::a('修改',['rbac/edit-role','name'=>$model->name],['class'=>'btn btn-warning btn-xs'])?>
                <?=\yii\bootstrap\Html::a('删除',['rbac/del-role','name'=>$model->name],['class'=>'btn btn-danger btn-xs'])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
