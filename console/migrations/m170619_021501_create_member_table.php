<?php

use yii\db\Migration;

/**
 * Handles the creation of table `member`.
 */
class m170619_021501_create_member_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('member', [
            'id' => $this->primaryKey(),
            //用户名
            'username'=>$this->string(50)->comment('用户名'),
            'auth_key'=>$this->string(32),
            //密码
            'password_hash'=>$this->string(100)->comment('密码'),
            //邮箱
            'email'=>$this->string(100)->comment('邮箱'),
            //电话
            'tel'=>$this->string(11)->comment('电话'),
            //最后登陆时间
            'last_login_time'=>$this->integer()->comment('最后登陆时间'),
            //最后登录IP
            'last_login_ip'=>$this->integer()->comment('最后登录IP'),
            //状态
            'status'=>$this->smallInteger(2)->comment('状态'),
            //添加时间
            'created_at'=>$this->integer()->comment('添加时间'),
            //修改时间
            'updated_at'=>$this->integer()->comment('修改时间'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('member');
    }
}
