<?php

use yii\db\Migration;

/**
 * Handles the creation of table `cart`.
 */
class m170623_074605_create_cart_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('cart', [
            'id' => $this->primaryKey(),
            //商品ID
            'goods_id'=>$this->integer()->comment('商品ID'),
            //商品数量
            'goods_num'=>$this->integer()->comment('商品数量'),
            //用户ID
            'member_id'=>$this->integer()->comment('用户ID'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('cart');
    }
}
