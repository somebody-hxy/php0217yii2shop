<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_category`.
 */
class m170609_005043_create_goods_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_category', [
            'id' => $this->primaryKey(),
            //树ID
            'tree'=>$this->integer()->comment('树ID'),
            //左值
            'lft'=>$this->integer()->comment('左值'),
            //右值
            'rgt'=>$this->integer()->comment('右值'),
            //层级
            'depth'=>$this->integer()->comment('层级'),
            //名称
            'name'=>$this->string(50)->comment('名称'),
            //上级分类ID
            'parent_id'=>$this->integer()->comment('上级分类ID'),
            //简介
            'intro'=>$this->text()->comment('简介'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods_category');
    }
}
