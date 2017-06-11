<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods`.
 */
class m170610_064803_create_goods_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods', [
            'id' => $this->primaryKey(),
            //商品名称
            'name'=>$this->string(20)->notNull()->comment('商品名称'),
            //货号
            'sn'=>$this->string(20)->notNull()->comment('货号'),
            //LOGO图片
            'logo'=>$this->string(255)->comment('LOGO图片'),
            //商品分类id
            'goods_category_id'=>$this->integer()->comment('商品分类id'),
            //品牌分类
            'brand_id'=>$this->integer()->comment('品牌分类'),
            //市场价格
            'market_price'=>$this->decimal(10,2)->comment('市场价格'),
            //商品价格
            'shop_price'=>$this->decimal(10,2)->comment('商品价格'),
            //库存
            'stock'=>$this->integer()->comment('库存'),
            //是否在售
            'is_on_sale'=>$this->integer()->comment('是否在售'),
            //状态
            'status'=>$this->smallInteger(2)->comment('状态'),
            //排序
            'sort'=>$this->integer()->comment('排序'),
            //创建时间
            'create_time'=>$this->integer()->comment('创建时间'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods');
    }
}
