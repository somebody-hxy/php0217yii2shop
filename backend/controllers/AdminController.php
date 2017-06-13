<?php

namespace backend\controllers;

class AdminController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    //添加数据
    public function actionAdd()
    {
        return $this->render('add');
    }
    //修改数据
    public function actionEdit()
    {
        return $this->render('add');
    }
    //删除数据
    public function actionDel()
    {
        return $this->redirect('admin/index');
    }
}
