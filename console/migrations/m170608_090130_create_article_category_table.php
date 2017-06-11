<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_category`.
 */
class m170608_090130_create_article_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article_category', [
            'id' => $this->primaryKey(),
            //名称
            'name'=>$this->string(50)->notNull()->comment('名称'),
            //简介
            'intro'=>$this->text()->comment('简介'),
            //sort排序
            'sort'=>$this->integer()->comment('排序'),
            //status状态
            'status'=>$this->smallInteger(2)->comment('状态'),
            //帮助文档
            'is_help'=>$this->smallInteger(2)->comment('是否为帮助文档'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article_category');
    }
}
