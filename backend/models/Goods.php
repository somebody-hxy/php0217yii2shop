<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "goods".
 *
 * @property integer $id
 * @property string $name
 * @property string $sn
 * @property string $logo
 * @property integer $goods_category_id
 * @property integer $brand_id
 * @property string $market_price
 * @property string $shop_price
 * @property integer $stock
 * @property integer $is_on_sale
 * @property integer $status
 * @property integer $sort
 * @property integer $create_time
 */
class Goods extends \yii\db\ActiveRecord
{

    public static $status_options = [1=>'正常',0=>'隐藏'];
    public static $sales_options = [1=>'在售',0=>'下架'];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
    }

    public static function getCategoryOptions()
    {
        return ArrayHelper::map(GoodsCategory::find()->asArray()->all(),'id','name');
    }
    public static function getBrandOptions()
    {
        return ArrayHelper::map(Brand::find()->where(['status'=>1])->asArray()->all(),'id','name');
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'sn'], 'required'],
            [['goods_category_id', 'brand_id', 'stock', 'is_on_sale', 'status', 'sort', 'create_time'], 'integer'],
            [['market_price', 'shop_price'], 'number'],
            [['name', 'sn'], 'string', 'max' => 20],
            [['logo'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '商品名称',
            'sn' => '货号',
            'logo' => 'LOGO图片',
            'goods_category_id' => '商品分类id',
            'brand_id' => '品牌分类',
            'market_price' => '市场价格',
            'shop_price' => '商品价格',
            'stock' => '库存',
            'is_on_sale' => '是否在售',
            'status' => '状态',
            'sort' => '排序',
            'create_time' => '创建时间',
        ];
    }
}
