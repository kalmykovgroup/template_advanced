<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'eO76Q7kQVlhG2IWXnI-fv9SamBLmoID3',
        ],
    ],
];

/**
 * @param array $config
 * @return array
 */
function extracted(array $config): array
{
    if (!YII_ENV_TEST) {
        // configuration adjustments for 'dev' environment
        $config['bootstrap'][] = 'debug';
        $config['modules']['debug'] = [
            'class' => \yii\debug\Module::class,
        ];

        $config['bootstrap'][] = 'gii';
        $config['modules']['gii'] = [
            'class' => \yii\gii\Module::class,
        ];
    }

    return $config;
}

return extracted($config);
