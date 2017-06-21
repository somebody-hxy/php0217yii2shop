<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m170618_020448_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            //菜单名称
            'label'=>$this->string(50)->comment(''),
            //URL地址
            'url'=>$this->string(255)->comment(''),
            //父id
            'parent_id'=>$this->string(50)->comment(''),
            //排序
            'sort'=>$this->string(50)->comment(''),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}
