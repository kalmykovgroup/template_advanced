<?php

namespace frontend\controllers;

use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\Exception;
use yii\base\InvalidArgumentException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;

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
                        'actions' => ['logout'],
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

                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
            'captcha' => [
                'class' => \yii\captcha\CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }



    public function actionLogin(): Response|false|string //Логин
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

    public function actionSignup(): Response|string //Регистрация
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionLogout(): Response //Выход
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }


    /**Оправляем токен для сброса пароля на почту
     * Запрашивает сброс пароля.
     *
     * @return Response|string
     * @throws Exception
     */
    public function actionRequestPasswordReset(): Response|string //Оправляем токен на почту, для сброса пароля
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) { //Проверяем данные на валидность
            if ($model->sendEmail()) { //Отправляем токен восстановления на почту
                Yii::$app->session->setFlash('success', 'Проверьте свою электронную почту для получения дальнейших инструкций.');
                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', 'К сожалению, мы не можем сбросить пароль для указанного адреса электронной почты.');
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     *Сбрасывает пароль.
     * Сюда прилетает пользователь по ссылки из элетронной почты. для сброса пароля
     * @param string $token
     * @return string|Response
     * @throws BadRequestHttpException
     * @throws Exception
     */
    public function actionResetPassword(string $token): string|Response //По ссылке из почты с токеном для сброса пароля
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'Пароль сохранен.');

            return $this->redirect(Url::to(['auth/login']));
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     * Сюда пререходит пользователь, когда нажимает на ссылку подтверждения емайл
     * @param string $token
     * @return Response
     *@throws BadRequestHttpException
     */
    public function actionVerifyEmail(string $token): Response //По ссылке из почты с токеном для подтверждения электронной почты
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($model->verifyEmail()) {
            Yii::$app->session->setFlash('success', 'Ваш адрес электронной почты успешно был подтвержден!');
            return $this->refresh();
        }

        Yii::$app->session->setFlash('error', 'К сожалению, произошел сбой подтверждения. Мы уже об этом знаем, наши специалисты приступили к работе.');
        return $this->refresh();
    }

    /**
     * Resend verification email
     *
     * @return string|Response
     */
    public function actionResendVerificationEmail(): string|Response //Повторная отправка подтверждающего сообщения электронной почты
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Проверьте свою электронную почту для получения дальнейших инструкций.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'К сожалению, мы не можем повторно отправить электронное письмо с подтверждением на указанный адрес электронной почты.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }

}