<?php

namespace frontend\models;

use Yii;


/**
 * This is the model class for table "address".
 *
 * @property integer $id
 * @property string $username
 * @property string $location
 * @property string $detail_address
 * @property string $tel
 * @property integer $is_default_address
 */
class Address extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username'], 'required'],
            [['is_default_address'], 'integer'],
            [['username', 'detail_address'], 'string', 'max' => 50],
            [['province_id','city_id','district_id'], 'string', 'max' => 50],
            [['tel'], 'string', 'max' => 11],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '收货人',
            'province_id' => '所在省',
            'city_id' => '所在市',
            'district_id' => '所在地区',
            'detail_address' => '详细地址',
            'tel' => '电话',
            'is_default_address' => '默认地址',
        ];
    }

//    public function beforeSave($insert){
//        $member_id=\Yii::$app->user->id;
//        $query=Address::find()->andWhere(['member_id'=>$member_id]);
//
//        return parent::beforeSave($insert);
//    }

    public function getProvince(){
        return $this->hasOne(Locations::className(),['id'=>'province_id']);
    }
    public function getCity(){
        return $this->hasOne(Locations::className(),['id'=>'city_id']);
    }
    public function getDistrict(){
        return $this->hasOne(Locations::className(),['id'=>'district_id']);
    }
}
