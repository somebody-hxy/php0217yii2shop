<?php

use yii\db\Migration;

/**
 * Handles the creation of table `admin`.
 */
class m170612_114146_create_admin_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('admin', [
            'id' => $this->primaryKey(),
            //用户名
            'username'=>$this->string(30)->notNull()->comment('用户名'),
            //密码
            'password'=>$this->string(100)->notNull()->comment('密码'),
            //状态
            'status'=>$this->smallInteger(2)->comment('状态'),
            //添加时间
            'create_time'=>$this->integer()->comment('添加时间'),
            //最后登陆时间
            'last_login_time'=>$this->integer()->comment('最后登陆时间'),
            //最后登录IP
            'last_login_ip'=>$this->string(50)->comment('最后登录IP'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('admin');
    }
}
