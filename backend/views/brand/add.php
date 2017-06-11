<?php
use yii\web\JsExpression;

$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'intro')->textarea();
echo $form->field($model,'logo')->hiddenInput();
/**echo $form->field($model,'imgFile')->fileInput();
if($model->logo){
    echo "<img src='$model->logo' width='80'/>";
}*/
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
echo \xj\uploadify\Uploadify::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'width' => 120,
        'height' => 40,
        'onUploadError' => new JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadSuccess' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        console.log(data.fileUrl);
        //将上传成功的图片地址（data.fileUrl）写入img标签
        $("#img_logo").attr("src",data.fileUrl).show();
        //将上传成功的图片地址（data.fileUrl）写入logo中
        $("#brand-logo").val(data.fileUrl);
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
echo $form->field($model,'sort');
echo $form->field($model,'status')->radioList(['0'=>'隐藏','1'=>'正常']);
//验证码
/**echo $form->field($model,'code')->widget(\yii\captcha\Captcha::className(),[
    'captchaAction'=>'account/captcha',
    'template'=>'<div class="row"><div class="col-xs-2">{image}</div><div class="col-xs-2">{input}</div></div>'
]);*/
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();