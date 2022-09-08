<?php

namespace app\assets;

use yii\web\AssetBundle;

class CommentsAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css',
        'css/style.css',
        'css/adaptive.css'
    ];
    public $js = [
        'js/comments/script.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        //'yii\bootstrap5\BootstrapAsset'
    ];
}
