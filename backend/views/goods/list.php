<?php
echo \yii\bootstrap\Html::a('返回商品列表',['goods/index'],['class'=>'btn btn-info','id'=>'add']);?>
<div>
    <h3><?=$goods->name?></h3>
    <p><?=$list->content?></p>
</div>