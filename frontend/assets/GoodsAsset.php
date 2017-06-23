<?php
namespace frontend\assets;

use yii\web\AssetBundle;

class GoodsAsset extends AssetBundle{

    public $basePath='@webroot';
    public $baseUrl='@web';
    //需要加载的CSS文件
    public $css = [
        'style/base.css',
        'style/global.css',
        'style/header.css',
        'style/goods.css',
        'style/common.css',
        'style/bottomnav.css',
        'style/footer.css',
    ];
    //需要加载的js文件
    public $js = [
        'js/header.js',
        'js/goods.js',
        'js/jqzoom-core.js',
    ];
    //需要依赖的文件
    public $depends = [
        //'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
        'yii\web\JqueryAsset',
    ];
}