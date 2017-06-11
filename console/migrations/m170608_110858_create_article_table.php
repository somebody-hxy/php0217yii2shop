<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article`.
 */
class m170608_110858_create_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article', [
            'id' => $this->primaryKey(),
            //名称
            'name'=>$this->string(50)->notNull()->comment('名称'),
            //简介
            'intro'=>$this->text()->comment('简介'),
            //文章分类ID
            'article_category_id'=>$this->integer()->comment('文章分类ID'),
            //排序
            'sort'=>$this->integer()->comment('排序'),
            //状态
            'status'=>$this->smallInteger(2)->comment('状态'),
            //添加时间
            'create_time'=>$this->integer()->comment('添加时间'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article');
    }
}
