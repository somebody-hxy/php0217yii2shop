<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'goods_id');
echo $form->field($model,'goods_name')->dropDownList(\backend\models\GoodsImg::getGoodsOptions(),['prompt'=>'=请商品名称分类=']);
echo $form->field($model,'goods_logo')->hiddenInput();
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
        $("#goodsimg-goods_logo").val(data.fileUrl);
    }
}
EOF
        ),
    ]
]);
if($model->goods_logo){
    echo \yii\helpers\Html::img(''.$model->goods_logo,['width'=>'80']);
}else{
    echo \yii\helpers\Html::img(''.$model->goods_logo,['style'=>'display:none','id'=>'img_logo','width'=>'80']);
}
echo $form->field($model,'goods_status')->radioList(\backend\models\GoodsImg::$status_options);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();