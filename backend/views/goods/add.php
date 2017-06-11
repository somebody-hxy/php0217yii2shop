<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'sn');
echo $form->field($model,'goods_category_id')->dropDownList(\backend\models\Goods::getCategoryOptions(),['prompt'=>'=请选择分类=']);
echo $form->field($model,'brand_id')->dropDownList(\backend\models\Goods::getBrandOptions(),['prompt'=>'=请选择分类=']);
echo $form->field($model,'market_price');
echo $form->field($model,'shop_price');
echo $form->field($model,'stock');
echo $form->field($model,'is_on_sale')->radioList(\backend\models\Goods::$sales_options);
echo $form->field($model,'status')->radioList(\backend\models\Goods::$status_options);
echo $form->field($model,'sort');
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();