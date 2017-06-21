<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property string $label
 * @property string $url
 * @property string $parent_id
 * @property string $sort
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }
    //查询所有的商品分类
    public static function getMenu()
    {
//        return ArrayHelper::merge([['id'=>0,'name'=>'顶级菜单','parent_id'=>0]],self::find()->where(['parent_id'=>0])->asArray()->all());
        return ArrayHelper::map(self::find()->where(['parent_id'=>0])->asArray()->all(),'id','label');
    }
     /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['label','parent_id','sort'],'required'],
            [['label', 'parent_id', 'sort'], 'string', 'max' => 50],
            [['url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'label' => '菜单名称',
            'url' => '路由',
            'parent_id' => '父ID',
            'sort' => '排序',
        ];
    }
//获取子菜单
    public function getChildren()
    {
        return $this->hasMany(self::className(),['parent_id'=>'id']);
    }
}
