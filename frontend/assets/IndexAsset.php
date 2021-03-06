<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class IndexAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    //需要加载的CSS文件
    public $css = [
        'style/base.css',
        'style/global.css',
        'style/header.css',
        'style/index.css',
        'style/bottomnav.css',
        'style/footer.css',
    ];
    //需要加载的js文件
    public $js = [
        //'js/jquery-1.8.3.min.js',
        'js/header.js',
        'js/index.js',
    ];
    //需要依赖的文件
    public $depends = [
        //'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
        'yii\web\JqueryAsset',
    ];
}
