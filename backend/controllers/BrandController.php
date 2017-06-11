<?php
namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\web\UploadedFile;
use xj\uploadify\UploadAction;
use crazyfd\qiniu\Qiniu;
class BrandController extends Controller{

    public function actionIndex(){
        $query=Brand::find();
        $total=$query->count();
        $page=new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>1,
        ]);
        $model=$query->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',['model'=>$model,'page'=>$page]);
    }
    //添加品牌
    public function actionAdd(){
        $model=new Brand();
        $request=\Yii::$app->request;
        if($request->isPost){
            //加载数据
            $model->load($request->post());
//            var_dump($request->post());exit;
            //图片
//            $model->imgFile=UploadedFile::getInstance($model,'imgFile');
            //验证数据
            if($model->validate()){
                //图片文件名
//                $fileName='/images/brand/'.uniqid().'.'.$model->imgFile->extension;
//                $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);
//                $model->logo=$fileName;
                $model->save();
//                $model->save(false);
                \Yii::$app->session->setFlash('success','添加品牌成功');
                return $this->redirect(['brand/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    //修改品牌
    public function actionEdit($id){
        $model=Brand::findOne(['id'=>$id]);
        $request=\Yii::$app->request;
        if($request->isPost){
            //加载数据
            $model->load($request->post());
            //图片
//            $model->imgFile=UploadedFile::getInstance($model,'imgFile');
            //验证数据
            if($model->validate()){
                //图片文件名
//                $fileName='/images/brand/'.uniqid().'.'.$model->imgFile->extension;
//                $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);
//                $model->logo=$fileName;
                $model->save();
//                $model->save(false);
                \Yii::$app->session->setFlash('success','修改品牌成功');
                return $this->redirect(['brand/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    //删除品牌
    public function actionDel($id){
        $model=Brand::findOne(['id'=>$id]);
//        var_dump($model);exit;
        $model->status=-1;
        $model->save();
        \Yii::$app->session->setFlash('success','删除品牌成功');
        return $this->redirect(['brand/index']);
    }
    //uploadify的配置
    public function actions() {
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                //'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
                /**'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filename = sha1_file($action->uploadfile->tempName);
                    return "{$filename}.{$fileext}";
                },*/
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png','gif'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    //七牛云配置
                    $imgUrl=$action->getWebUrl();//图片地址

                    $ak = 'Z7cY0sCYJmwk1Mn5cobYajEhojrhaX9EWLrdwHKo';
                    $sk = 'ZZVZyqVMOwfmpH3hY_JFYKK6RF1FHu1Kb9IAxWs2';
                    $domain = 'http://or9un98uk.bkt.clouddn.com/';
                    $bucket = 'php0217';

                    $qiniu = new Qiniu($ak, $sk,$domain, $bucket);
                    //要上传的文件
                    $fileName=\Yii::getAlias('@webroot').$imgUrl;
                    $key = $imgUrl;
                    //上传文件
                    $re=$qiniu->uploadFile($fileName,$key);
                    //var_dump($re);
                    //从七牛云上获取图片地址
                    $url = $qiniu->getLink($key);
                    //将获取到的图片地址输出到页面
                    $action->output['fileUrl'] =  $url;
//                    var_dump($url);

                   //调用七牛云组件，将图片上传到七牛云

//                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
//                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
//                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                },
            ],
        ];
    }

    //测试七牛云的配置
    public function actionTest(){
        $ak = 'Z7cY0sCYJmwk1Mn5cobYajEhojrhaX9EWLrdwHKo';
        $sk = 'ZZVZyqVMOwfmpH3hY_JFYKK6RF1FHu1Kb9IAxWs2';
        $domain = 'http://or9un98uk.bkt.clouddn.com/';
        $bucket = 'php0217';

        $qiniu = new Qiniu($ak, $sk,$domain, $bucket);
//        $key = time();
        //要上传的文件
        $fileName=\Yii::getAlias('@webroot'.'/upload/test.jpg');
        $key = 'test.jpg';
        $re=$qiniu->uploadFile($fileName,$key);
        //var_dump($re);exit;
        $url = $qiniu->getLink($key);
    }

}