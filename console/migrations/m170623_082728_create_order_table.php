<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order`.
 */
class m170623_082728_create_order_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('order', [
            'id' => $this->primaryKey(),
            'member_id'=>$this->integer()->comment('用户ID'),
            'name'=>$this->string(50)->comment('收货人'),
            'province'=>$this->string(20)->comment('省'),
            'city'=>$this->string(20)->comment('市'),
            'area'=>$this->string(20)->comment('县'),
            'address'=>$this->string(255)->comment('详细地址'),
            'tel'=>$this->string(11)->comment('电话号码'),
            'delivery_id'=>$this->integer()->comment('配送方式id'),
            'delivery_name'=>$this->string(20)->comment('配送方式名称'),
            'delivery_price'=>$this->decimal(9,2)->comment('配送方式价格'),
            'payment_id'=>$this->integer()->comment('支付方式id'),
            'payment_name'=>$this->string(20)->comment('支付方式名称'),
            'total'=>$this->decimal(9,2)->comment('订单金额'),
            'status'=>$this->smallInteger(4)->comment('订单状态'),
            'trade_no'=>$this->string(30)->comment('第三方支付交易号'),
            'create_time'=>$this->integer()->comment('创建时间'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('order');
    }
}
