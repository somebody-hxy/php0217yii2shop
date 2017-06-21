<?php
namespace backend\models;

use yii\base\Model;
use yii\rbac\Permission;

class PermissionForm extends Model{

    //权限名称
    public $name;
    //权限描述
    public $description;

    public function rules(){
        return[
           [['name','description'],'required'],
        ];
    }

    public function attributeLabels(){
        return [
            'name'=>'权限名称',
            'description'=>'权限描述',
        ];
    }

    //添加权限
    public function addPermission(){
        $authManager=\Yii::$app->authManager;
        //创建权限
        //判断权限是否存在
        if($authManager->getPermission($this->name)){
            $this->addError('name','权限已存在');
        }else{
            $permission=$authManager->createPermission($this->name);
            $permission->description=$this->description;
            //将权限保存到数据库
            return $authManager->add($permission);
        }
        return false;
    }
    //从权限中加载数据
    public function loadData(Permission $permission){
        $this->name=$permission->name;
        $this->description=$permission->description;
    }
    //修改权限
    public function updatePermission($name){
        $authManager=\Yii::$app->authManager;
        //获取要修改的权限
        $permission=$authManager->getPermission($name);
        //获取当前输入的权限
        $per=$authManager->getPermission($this->name);
        //判断修改后的权限名称是否存在
        if($name != $this->name && $per){
            $this->addError('name','权限已存在');
        }else{
            $permission->name=$this->name;
            $permission->description=$this->description;
            //更新权限
            return $authManager->update($name,$permission);
        }
        return false;
    }
}