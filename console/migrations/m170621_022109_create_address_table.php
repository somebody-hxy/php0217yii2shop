<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m170621_022109_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            //用户
            'member_id'=>$this->integer()->comment('用户名id'),
            //用户
            'username'=>$this->string(50)->notNull()->comment('用户名'),
            //所在地区
            'province'=>$this->string(50)->comment('所在省'),
            //所在地区
            'city'=>$this->string(50)->comment('所在市'),
            //所在地区
            'area'=>$this->string(50)->comment('所在地区'),
            //详细地址
            'detail_address'=>$this->string(50)->comment('详细地址'),
            //电话
            'tel'=>$this->string(11)->comment('电话'),
            //默认地址
            'is_default_address'=>$this->smallInteger(2)->comment('默认地址'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
