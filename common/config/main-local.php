<?php

return [
    'components' => [
        'db' => [
            'class' => \yii\db\Connection::class,
            'dsn' => 'mysql:host=localhost;dbname=a0788973_kalmykov_group',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.//n😠🎍🎋💐💋💏💍💏🎅🎆
            'useFileTransport' => false,
            'transport' => [
               // 'class' => 'Swift_SmtpTransport',
                'scheme' => 'smtps',
                'host' => 'smtp.kalmykov-group.ru',
                'username' => 'admin@kalmykov-group.ru',
                'password' => 'vanja199617123',
                'port' => '465',// Порт 25 тоже очень распространенный порт
               // 'encryption' => 'ssl',//Он часто используется, проверьте характеристики вашего провайдера или почтового сервера
                //'dsn' => 'native://default',
               // q4gZZQcuRz5ca7Xx1Dbn
            ],
        ],
        'authClientCollection' => [
            'class'   => \yii\authclient\Collection::class,
            'clients' => [
                'vkontakte' => [
                    'class' => 'yii\authclient\clients\VKontakte',
                    'clientId' => '7461035',
                    'clientSecret' => 'qylgWhMyi08adQXJGN8F',
                    'scope' => 'email'
                ],
                'github' => [
                    'class' => 'yii\authclient\clients\GitHub',
                    'clientId' => 'github_client_id',
                    'clientSecret' => 'github_client_secret',
                ],

                'facebook' => [
                    'class'        => 'yii\authclient\clients\Facebook',
                    'clientId' => '2625490567688768',
                    'clientSecret' => '0ecee07cc255e738cb358409e4788a52',
                ],
                'google' => [
                    'class'        => 'yii\authclient\clients\Google',
                    'clientId'     => 'gOGKd__MZC9FwZGCu4wbQfBFk',
                    'clientSecret' => '169125874225-n532aghiqhne1vrclrbc8gh7g45cd46r.apps.googleusercontent.com',
                ],
                'yandex' => [
                    'class' => 'yii\authclient\clients\Yandex',
                    'clientId' => 'yandex_client_id',
                    'clientSecret' => 'yandex_client_secret',
                    'normalizeUserAttributeMap' => [
                        'email' => function ($attributes) {
                            return $attributes['email']
                                ?? $attributes['default_email']
                                ?? current($attributes['emails'] ?? [])
                                ?: null;
                        }
                    ]
                ],
                'twitter' => [
                    'class' => 'yii\authclient\clients\Twitter',
                    'attributeParams' => [
                        'include_email' => 'true'
                    ],
                    'consumerKey' => 'twitter_consumer_key',
                    'consumerSecret' => 'twitter_consumer_secret',
                ],
            ],
        ],

    ],
];
