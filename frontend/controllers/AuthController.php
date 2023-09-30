<?php

namespace frontend\controllers;

use app\models\Log;
use common\models\LoginForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;

class AuthController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [' '],
                'rules' => [
                    [
                        'actions' => [''],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    throw new \Exception('You are not allowed to access this page');
                }
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                    //'login' => ['post'],
                ],
            ],
        ];
    }


    public function actionLogin(): \yii\web\Response|false|string
    {
        $cookies = Yii::$app->request->cookies;
        if (Yii::$app->request->isAjax) {
            try {
                $model = new  LoginForm();

                if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->login()) {
                    return json_encode(['success' => true, 'login_referer' => $cookies->get('login_referer')->value]);

                } else {
                    return json_encode(['success' => false, 'errors' => $model->errors]);
                }

            } catch (\Exception $e) {
                return json_encode(['success' => false, 'errors' => ['unknown' => $e->getMessage()]]);
            }
        }else{

            if (($cookies->get('login_referer')) !== null && ($cookies->get('login_referer')->value != ($_SERVER['HTTP_REFERER'] ?? $_SERVER['HTTP_HOST'] . '/')) || $cookies->get('login_referer') === null) {

                Yii::$app->response->cookies->add(new \yii\web\Cookie([
                    'name' => 'login_referer',
                    'value' => ($_SERVER['HTTP_REFERER'] ?? Url::home()),
                    'httpOnly' => true, //Запретить чтение через стороние языки(js) защита от XSS-атак.
                    'expire' => null, //Время жизни, до закрытия браузера
                ]));
            }

            $model = new LoginForm();
            if ($model->load(Yii::$app->request->post()) && $model->login()) {
                return $this->goBack();
            }

            $model->password = '';
            return $this->render('login', [
                'model' => $model,
            ]);
        }


    }

}