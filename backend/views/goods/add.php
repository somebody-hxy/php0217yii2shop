<?php
/**
 * @var $this \yii\web\View
 */
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'logo')->hiddenInput();
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
echo \xj\uploadify\Uploadify::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'width' => 120,
        'height' => 40,
        'onUploadError' => new \yii\web\JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadSuccess' => new \yii\web\JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        console.log(data.fileUrl);
        //将上传成功的图片地址（data.fileUrl）写入img标签
        $("#img_logo").attr("src",data.fileUrl).show();
        //将上传成功的图片地址（data.fileUrl）写入logo中
        $("#goods-logo").val(data.fileUrl);
    }
}
EOF
        ),
    ]
]);
if($model->logo){
    echo \yii\helpers\Html::img(''.$model->logo,['width'=>'80']);
}else{
    echo \yii\helpers\Html::img(''.$model->logo,['style'=>'display:none','id'=>'img_logo','width'=>'80']);
}
echo $form->field($model,'goods_category_id')->hiddenInput();
echo '<ul id="treeDemo" class="ztree"></ul>';
echo $form->field($model,'brand_id')->dropDownList(\backend\models\Goods::getBrandOptions(),['prompt'=>'=请选择分类=']);
echo $form->field($model,'market_price');
echo $form->field($model,'shop_price');
echo $form->field($model,'stock');
echo $form->field($model,'is_on_sale')->radioList(\backend\models\Goods::$sales_options);
echo $form->field($model,'status')->radioList(\backend\models\Goods::$status_options);
echo $form->field($model,'sort');
//百度ueditor
echo $form->field($goodsintro,'content')->widget('kucha\ueditor\UEditor',[]);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
//加载静态资源
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);
$zNodes=\yii\helpers\Json::encode($category);
$js=new \yii\web\JsExpression(
    <<<JS
    var zTreeObj;
    //配置参数
    var setting={
        data: {
            simpleData: {
                enable: true,
                idKey: "id",
                pIdKey: "parent_id",
                rootPId: 0
            }
        },
        callback:{
            onClick:function(event,treeId,treeNode){
//                console.log(treeNode.id);
                //将选中的节点的id赋值给表单的parent_id
                $("#goods-goods_category_id").val(treeNode.id);
            }
        }
    };
    // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
    var zNodes = {$zNodes};
    zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
    zTreeObj.expandAll(true);//展开所有节点
    //获取当前节点的父节点，根据ID查找
    var node=zTreeObj.getNodeByParam("id",$("#goods-goods_category_id").val(),null);
    zTreeObj.selectNode(node);//选中当前节点的父节点
JS
);
$this->registerJs($js);



