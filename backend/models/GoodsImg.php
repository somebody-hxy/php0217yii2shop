<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "goods_img".
 *
 * @property integer $id
 * @property integer $goods_id
 * @property integer $goods_status
 * @property string $goods_logo
 */
class GoodsImg extends \yii\db\ActiveRecord
{
    public static $status_options = [1=>'正常',0=>'隐藏'];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_img';
    }
    public function getGoods()
    {
        //hasOne的第二个参数【k=>v】 k代表分类的主键（id） v代表商品分类在当前对象的关联id
        return $this->hasOne(Goods::className(),['id'=>'goods_id']);
    }
    public static function getGoodsOptions()
    {
        return ArrayHelper::map(Goods::find()->where(['status'=>1])->asArray()->all(),'id','name');
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'goods_status'], 'integer'],
            [['goods_logo'], 'string', 'max' => 255],
//            [['goods_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'goods_id' => '商品名称',
//            'goods_name' => '商品名称',
            'goods_status' => '商品状态',
            'goods_logo' => '商品logo',
        ];
    }
}
