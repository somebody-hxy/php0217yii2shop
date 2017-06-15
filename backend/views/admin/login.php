<div class="row">
    <div class="col-lg-6">
<?php
/* @var $this yii\web\View */
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username');
echo $form->field($model,'password_hash')->passwordInput();
//验证码
echo $form->field($model,'code')->widget(\yii\captcha\Captcha::className(),[
    'captchaAction'=>'admin/captcha',
    'template'=>'<div class="row"><div class="col-xs-2">{image}</div><div class="col-xs-2">{input}</div></div>'
]);
echo $form->field($model, 'rememberMe')->checkbox();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
?>
    </div>
</div>
