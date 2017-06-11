<?php
namespace backend\models;

use yii\db\ActiveRecord;

class Brand extends ActiveRecord{
    //logo上传对象
//    public $imgFile;
    public static  function tableName(){
        return 'brand';
    }

    public function rules(){
        return[
            [['name'],'required'],
            [['intro'],'string'],
            [['sort','status'],'integer'],
            [['name'],'string','max'=>50],
            [['logo'],'string','max'=>255],
//            ['imgFile','file','extensions'=>['jpg','png','gif'],'skipOnEmpty'=>false],
        ];
    }

    public function attributeLabels(){
        return [
            'name'=>'名称',
            'intro'=>'简介',
            'logo'=>'logo',
            'sort'=>'排序',
            'status'=>'状态',
        ];
    }
}