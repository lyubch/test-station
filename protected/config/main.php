<?php

$db     = require __DIR__ . '/db.php';
$params = require __DIR__ . '/params.php';

$config = [
    'id' => 'test-station',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
	'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'request' => [
			'class'                => \yii\web\Request::class,
            'enableCsrfValidation' => false,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => false,
            'showScriptName'  => false,
//            'rules' => [
//				\yii\web\UrlManager::class,
//            ],
        ],
    ],
    'params' => $params,
];

function _assert($assertion, $description = null) {
	if (!$assertion) {
		if ($description !== null) {
			$message = "Assertion `$description` does not meet.";
		} else {
			$message = 'Assertion does not meet.';
		}
		throw new Exception($message);
	}
	if ($description !== null)  {
		_d($description);
	}
}

return $config;
