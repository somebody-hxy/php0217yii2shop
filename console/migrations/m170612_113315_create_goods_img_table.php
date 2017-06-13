<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_img`.
 */
class m170612_113315_create_goods_img_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_img', [
            'id' => $this->primaryKey(),
            //商品ID
            'goods_id'=>$this->integer()->comment('商品ID'),
            //商品名称
            'goods_name'=>$this->string(50)->comment('商品名称'),
            //商品状态
            'goods_status'=>$this->smallInteger(2)->comment('商品状态'),
            //商品logo
            'goods_logo'=>$this->string(255)->comment('商品logo'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods_img');
    }
}
