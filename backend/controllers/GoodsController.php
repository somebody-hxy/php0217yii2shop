<?php
namespace backend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsImg;
use backend\models\GoodsIntro;
use xj\uploadify\UploadAction;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class GoodsController extends Controller{

    public function actionIndex(){
        $key=isset($_GET['key'])? $_GET['key']: '';
        $query = Goods::find()->andWhere(['like','name',$key]);
//        $query = Goods::find();
//        if($name=\Yii::$app->request->get('name')){
//            $query->andWhere(['like','name',$name]);
//        }
//        if($sn=\Yii::$app->request->get('sn')){
//            $query->andWhere(['like','sn',$sn]);
//        }
        $total=$query->count();
        $page=new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>2,
        ]);
        $model=$query->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',['model'=>$model,'page'=>$page]);
    }
    //添加数据
    public function actionAdd(){
        $model=new Goods();
        $goodsintro=new GoodsIntro();
        $category=GoodsCategory::find()->asArray()->all();
        //查询goods_day_count表中的数据条数
        //$day=date('Ymd');
        //$count=GoodsDayCount::find()->where(['day'=>$day])->count();
        //$sn=$day.str_pad($num,4,STR_PAD_LEFT);
        /*$num=strlen($count);
        //判断$num的位数
        if($num==1){
            $sn=$day.'000'.($count+1);
        }elseif($num==2){
            $sn=$day.'00'.($count+1);
        }elseif($num==2){
            $sn=$day.'0'.($count+1);
        }else{
            $sn=$day.($count+1);
        }
        $goodsday=new GoodsDayCount();
        $goodsday=GoodsDayCount::findOne(['day'=>date('Y-m-d')])
        $goodsday->count=$count+1;
        */
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            $goodsintro->load($request->post());
            if($model->validate()){
                //判断date('Y-m-d')是否有值
                if($goodsday=GoodsDayCount::findOne(['day'=>date('Y-m-d')])){
                    $count=($goodsday->count)+1;
                    $sn=date('Ymd').str_pad($count,4,0,STR_PAD_LEFT);
                    $goodsday->count=$count;
                }else{
                    $sn=date('Ymd').str_pad(1,4,0,STR_PAD_LEFT);
                    $goodsday=new GoodsDayCount();
                    $goodsday->day=date('Y-m-d');
                    $goodsday->count=1;
                }
                $model->sn=$sn;
                $model->create_time=time();
                $model->save();
                $goodsday->save();
                //验证商品详情
                if($goodsintro->validate()){
                    $goodsintro->goods_id=$model->id;
                    $goodsintro->save();
                    \Yii::$app->session->setFlash('success','添加商品成功');
                    return $this->redirect(['goods/index']);
                }
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('add',['model'=>$model,'category'=>$category,'goodsintro'=>$goodsintro]);
    }
    //修改数据
    public function actionEdit($id){
        $model=Goods::findOne(['id'=>$id]);
        $goodsintro=GoodsIntro::findOne(['goods_id'=>$id]);
        $category=GoodsCategory::find()->asArray()->all();
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            $goodsintro->load($request->post());
            if($model->validate()){
                $model->save();
                //验证商品详情
                if($goodsintro->validate()){
                    $goodsintro->goods_id=$id;
                    $goodsintro->save();
                    \Yii::$app->session->setFlash('success','修改商品成功');
                    return $this->redirect(['goods/index']);
                }
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('add',['model'=>$model,'goodsintro'=>$goodsintro,'category'=>$category]);
    }
    //删除数据
    public function actionDel($id){
        $model=Goods::findOne(['id'=>$id]);
        $model->status=-1;
        $model->save();
        \Yii::$app->session->setFlash('success','删除商品成功');
        return $this->redirect(['goods/index']);
    }
    //商品详情页
    public function actionList($id){
        $goods=Goods::findOne(['id'=>$id]);
        $list=GoodsIntro::findOne(['goods_id'=>$id]);
        return $this->render('list',['list'=>$list,'goods'=>$goods]);
    }
    //商品相册
    public function actionGallery($id){
        $goods=Goods::findOne(['id'=>$id]);
        if($goods==null){
            throw new NotFoundHttpException('商品不存在');
        }
        return $this->render('gallery',['goods'=>$goods]);
    }
    //ajax删除图片
    public function actionDelGallery(){
        $id = \Yii::$app->request->post('id');
        $model = GoodsImg::findOne(['id'=>$id]);
        if($model && $model->delete()){
            return 'success';
        }else{
            return 'fail';
        }

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
                    $model = new GoodsImg();
                    $model->goods_id = \Yii::$app->request->post('goods_id');
                    $model->goods_logo = $action->getWebUrl();
                    $model->save();
                    $action->output['fileUrl'] = $model->goods_logo;
//                    $action->output['fileUrl'] = $action->getWebUrl();
//                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
//                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
//                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                },
            ],
        ];
    }
}