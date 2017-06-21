<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "admin".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property integer $status
 */
class Admin extends \yii\db\ActiveRecord implements IdentityInterface
{
    public static $status_options = [1=>'启用',0=>'禁用'];
    public $roles=[];//用户角色
    const SCENARIO_ADD = 'add';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username','email'], 'required'],
            ['password_hash','required','on'=>self::SCENARIO_ADD],
            [['status'], 'integer'],
            ['username', 'unique','message' => '用户名已存在'],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'string', 'max' => 30],
            [['password_hash'], 'string', 'max' => 100],
            [['last_login_ip'], 'string', 'max' => 50],
//            [['email'],'match','pattern'=>'/^\w+([-.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/','message'=>'邮箱格式不正确'],
            ['email', 'unique', 'message' => '邮箱名已存在'],
            [['email'],'email'],
            [['roles'], 'safe'],
            [['password_reset_token'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'auth_key' => 'Auth Key',
            'password_hash' => '密码',
            'password_reset_token' => 'Password Reset Token',
            'email' => '邮箱',
            'status' => '状态',
            'create_at' => '添加时间',
            'updated_at' => '更新时间',
            'last_login_time' => '最后登录时间',
            'last_login_ip' => '最后登录ip',
            'roles'=>'角色',
        ];
    }
    public function addRole($arr,$id)
    {
        $authManager = Yii::$app->authManager;
        foreach($arr as $v){
            if($authManager->getRole($v)){
                $authManager->assign($authManager->getRole($v),$id);
            }
        }
        return true;
    }

    public function updateRole($arr,$id)
    {
        $auth = Yii::$app->authManager;
        $auth->revokeAll($id);
        foreach($arr as $v){
            if($auth->getRole($v)){
                $auth->assign($auth->getRole($v),$id);
            }
        }
        return true;
    }

    public function loadData($role)
    {
        foreach ($role as $key=>$v){
            $this->roles[$key]=$key;
        }
    }
    public function beforeSave($insert){
        if($insert){
            $this->create_at=time();
            $this->status=1;
            //生成随机字符串
            $this->auth_key=Yii::$app->security->generateRandomString();
        }
        //给用户关联角色
//        if($this->roles){
//            $authManager = Yii::$app->authManager;
//            $authManager->revokeAll($this->id);
//            foreach ($this->roles as $roleName){
//                $role = $authManager->getRole($roleName);
//                if($role) $authManager->assign($role,$this->id);
//            }
//        }
        return parent::beforeSave($insert);
    }
    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        //通过ID获取账号
        return self::findOne(['id'=>$id]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        //获取当前账号的ID
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey()==$authKey;
    }
}
