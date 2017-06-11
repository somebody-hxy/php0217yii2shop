<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_detail`.
 */
class m170608_111916_create_article_detail_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article_detail', [
            //文章ID
            'article_id' => $this->primaryKey()->comment('文章ID'),
            //简介
            'content'=>$this->text()->comment('简介'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article_detail');
    }
}
