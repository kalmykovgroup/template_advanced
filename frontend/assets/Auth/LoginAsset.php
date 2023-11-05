<?php

namespace frontend\assets\Auth;

use yii\web\AssetBundle;


class LoginAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/auth/login.css',
    ];
    public $js = [
        'js/auth/login/login.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset'
    ];
}