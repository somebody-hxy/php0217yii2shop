<?php
namespace backend\models;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\rbac\Role;

class RoleForm extends Model{

    //角色名
    public $name;
    //角色描述
    public $description;
    //角色权限
    public $permissions=[];

    public function rules(){
        return [
            [['name','description'],'required'],
            ['permissions','safe'],
        ];
    }
    public function attributeLabels(){
        return [
            'name'=>'角色名',
            'description'=>'角色描述',
            'permissions'=>'角色权限',
        ];
    }
    //获取所有权限
    public static function getPermissionOptions(){
        $authManager=\Yii::$app->authManager;
        //获取所有权限
        return ArrayHelper::map($authManager->getPermissions(),'name','description');
    }
    //添加角色
    public function addRole(){
        $authManager=\Yii::$app->authManager;
        //判断角色是否存在
        if($authManager->getRole($this->name)){
            $this->addError('name','角色已存在');
        }else{
            $role=$authManager->createRole($this->name);
            $role->description=$this->description;
            if($authManager->add($role)){//保存到数据库
                //关联该角色的权限
                foreach($this->permissions as $permissionName){
                    $permission=$authManager->getPermission($permissionName);
                    if($permission){
                        $authManager->addChild($role,$permission);
                    }
                }
                return true;
            }
        }
        return false;
    }
    //获取所有的权限
    public function loadData(Role $role){
        $this->name=$role->name;
        $this->description=$role->description;
        //获取该角色对应的权限
        $permissions=\Yii::$app->authManager->getPermissionsByRole($role->name);
        foreach($permissions as $permission){
            $this->permissions[]=$permission->name;
        }
    }
    //修改角色
    public function updateRole($name){
        $authManager=\Yii::$app->authManager;
        $role=$authManager->getRole($name);
        $role->name=$this->name;
        $role->description=$this->description;
        //如果角色被修改，检查修改的角色名是否存在
        $ro=\Yii::$app->authManager->getRole($this->name);
        if($name !=$this->name && $ro){
            $this->addError('角色已存在');
        }else{
            if($authManager->update($name,$role)){//更新角色
                //去掉所有与该角色关联的权限
                $authManager->removeChildren($role);
                //关联该角色的权限
                foreach($this->permissions as $permissionName){
                    $permission=$authManager->getPermission($permissionName);
                    if($permission){
                        $authManager->addChild($role,$permission);
                    }
                }
                return true;
            }
        }
        return false;
    }

}