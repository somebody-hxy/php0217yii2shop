<?php
namespace backend\controllers;

use backend\models\PermissionForm;
use backend\models\RoleForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class RbacController extends Controller{
    //权限列表
    public function actionPermissionIndex(){
//        \Yii::$app->authManager->
        //获取所有的权限
        $models=\Yii::$app->authManager->getPermissions();
        return $this->render('permission-index',['models'=>$models]);
    }
    //添加权限
    public function actionAddPermission(){
        $model=new PermissionForm();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->addPermission()){
               \Yii::$app->session->setFlash('success','添加权限成功');
               return $this->redirect(['permission-index']) ;
            }
        }
        return $this->render('add-permission',['model'=>$model]);
    }
    //修改权限
    public function actionEditPermission($name){
        //查询权限
        $permission=\Yii::$app->authManager->getPermission($name);
        //判断权限是否存在
        if($permission==null){
            throw new NotFoundHttpException('权限不存在'); }
        $model=new PermissionForm();
        //将要修改的权限的值赋值给表单模型
        $model->loadData($permission);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->updatePermission($name)){
                \Yii::$app->session->setFlash('success','修改权限成功');
                return $this->redirect(['permission-index']) ;
            } }
        return $this->render('add-permission',['model'=>$model]);
    }
    //删除权限
    public function actionDelPermission($name){
        //查询权限
        $permission=\Yii::$app->authManager->getPermission($name);
        if($permission==null){
            throw new NotFoundHttpException('权限不存在');}
        //删除权限
        \Yii::$app->authManager->remove($permission);
        \Yii::$app->session->setFlash('success','删除权限成功');
        return $this->redirect(['permission-index']);
    }

    //角色
    //角色列表
    public function actionRoleIndex(){
        $authManager=\Yii::$app->authManager;
        $models=$authManager->getRoles();
        return $this->render('role-index',['models'=>$models]);
    }
    //添加角色
    public function actionAddRole(){
        $model=new RoleForm();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->addRole()){
                \Yii::$app->session->setFlash('success','添加角色成功');
                return $this->redirect(['role-index']);
            }
        }
        return $this->render('add-role',['model'=>$model]);
    }
    //修改角色
    public function actionEditRole($name){
        $role=\Yii::$app->authManager->getRole($name);
        if($role==null){
            throw new NotFoundHttpException('角色不存在');
        }
        $model=new RoleForm();
        //将数据在表单模型中显示
        $model->loadData($role);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->updateRole($name)){
                \Yii::$app->session->setFlash('success','修改角色成功');
                return $this->redirect(['role-index']);
            }
        }
        return $this->render('add-role',['model'=>$model]);
    }
    //删除角色
    public function actionDelRole($name){
        $role=\Yii::$app->authManager->getRole($name);
        if($role==null){
            throw new NotFoundHttpException('角色不存在');
        }
        //删除角色
        \Yii::$app->authManager->remove($role);
        \Yii::$app->session->setFlash('success','删除角色成功');
        return $this->redirect(['role-index']);
    }
    //用户关联角色
    public function actionUser()
    {
        $authManager = \Yii::$app->authManager;
        //获取所有角色
        $roles=$authManager->getRoles();
        //将id为1的用户，添加管理员角色
        $role= $authManager->getRole('admin');
        $authManager->assign($role,1);
//        $session=\Yii::$app->session;
//        $admin_id=$session->get('id');
        //修改用户关联的角色
        //去掉当前用户的所以关联角色
        //$authManager->revokeAll(1);

    }
}