<?php

namespace frontend\models;

use Yii;
use backend\models\Goods;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "cart".
 *
 * @property integer $id
 * @property integer $goods_id
 * @property integer $goods_num
 * @property integer $member_id
 */
class Cart extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cart';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['goods_id', 'amount', 'member_id'], 'required'],
            [['goods_id', 'amount', 'member_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'goods_id' => '商品ID',
            'amount' => '商品数量',
            'member_id' => '用户ID',
        ];
    }

    public function getGoods()
    {
        return $this->hasOne(Goods::className(),['id'=>'goods_id']);
    }

    //获取当前用户的所有地址
//    public function getAddress(){
//        return ArrayHelper::map(Address::find()->asArray()->all(),'member_id','name');
//    }

}
