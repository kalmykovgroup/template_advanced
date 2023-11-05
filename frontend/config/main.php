<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'errorHandler' => [
            'errorAction' => 'site/error',
            'maxSourceLines' => 20,
        ],
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'baseUrl' => '',
            'enableCsrfValidation'=>true,
            'enableCookieValidation'=>true,
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,

            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
            'enableSession' => true,
            'loginUrl' => ['auth/login'],

            'on afterLogin' => function(){ //
                         $user = Yii::$app->user->identity;
                         $session = Yii::$app->session;
                         $session->open();
                         $session->set('username', $user->name ?? $user->login ?? $user->email ?? $user->phone ?? "Unknown");
                }
        ],
        'session' => [
            // это имя файла cookie сеанса, используемого для входа во внешний интерфейс
            'name' => 'laravel',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                '<controller:\w+>/<action:\w+>/' => '<controller>/<action>',
            ],
        ],
    ],
    'params' => $params,
];
