<?php

namespace frontend\controllers;

use common\models\Auth;
use common\models\FullInfo;
use common\models\LoginForm;
use common\models\User;
use Exception;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\VerifyEmailForm;
use InvalidArgumentException;
use Yii;
use yii\authclient\clients\Facebook;
use yii\base\ErrorException;
use yii\base\InvalidRouteException;
use yii\bootstrap5\ActiveForm;
use yii\console\ErrorHandler;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

class AuthController extends Controller
{


    public function behaviors(): array
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
                'class' => 'yii\web\ErrorAction',
            ],
            'errorHandler' => [
                'errorAction' => 'errorHandler/error',
            ],
            'captcha' => [
                'class' => \yii\captcha\CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }


    /**
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function onAuthSuccess($client): void
    {
        $attributes = $client->getUserAttributes();

        /* @var $auth Auth */
        $auth = Auth::find()->where([
            'source' => $client->getId(),
            'source_id' => $attributes['id'],
        ])->one();

        if (Yii::$app->user->isGuest) {
            if ($auth) { // авторизация
                $user = $auth->user;
                Yii::$app->user->login($user);
            } else {
                $this->actionSignup($client); //передаем данные на регистрация
            }
        } else { // Пользователь уже зарегистрирован и авторизован (значит это просто привязка соц-сети к аккаунту)
            if (!$auth) { //Если эта соц-сеть не была привязана ранее, то проблем нет
                $auth = new Auth([
                    'user_id' => Yii::$app->user->id,
                    'source' => $client->getId(),
                    'source_id' => $attributes['id'],
                ]);
                $auth->save();// добавляем внешний сервис аутентификации к аккаунту
            }else{//Попытка привязать аккаунт соц-сети, который уже используеться.
                Yii::$app->getSession()->setFlash('error', [
                    Yii::t('common', "{client} уже используеться, обратитесь в поддержку.", ['client' => $client->getTitle()]),
                ]);
            }
        }
    }


    const ReferrerUrl = "referrer";
    const ReturnUrl = "return_url";
    const RedirectUrl  = "redirect_url";

    private function rememberBackUrl(): void
    {
        $session = Yii::$app->session;
        if (!$session->isActive) $session->open();

        $referrer = Yii::$app->request->referrer;
        $returnUrl = Yii::$app->user->returnUrl;

        $sessionReturn_Url = isset($session[self::ReturnUrl]) ? $session->get(self::ReturnUrl) : null;
        $sessionReferrer = isset($session[self::ReferrerUrl]) ? $session->get(self::ReferrerUrl) : null;
        $baseUrl = Yii::$app->request->getHostInfo();

        if(empty($referrer))//Мы попали по прямой ссылке
        {
            Yii::$app->session->set(self::ReferrerUrl, $baseUrl);
            Yii::$app->session->set(self::ReturnUrl, $baseUrl);
            Yii::$app->session->set(self::RedirectUrl, $baseUrl);
        }
        else if($referrer != Yii::$app->request->absoluteUrl && $referrer != $sessionReferrer) //Были изменения в referrer
        {//!empty Если мы переходим по навигации и проверяем что именно мы сами перейшли в логин
            Yii::$app->user->returnUrl = $baseUrl;
            Yii::$app->session->set(self::ReferrerUrl, $referrer);
            Yii::$app->session->set(self::RedirectUrl, $referrer);
        }
        else if(str_contains($returnUrl, $baseUrl) &&  $returnUrl != $sessionReturn_Url){ //Были изменения в returnUrl
            Yii::$app->session->set(self::ReturnUrl, $baseUrl);
            Yii::$app->user->returnUrl = $baseUrl;
            Yii::$app->session->set(self::RedirectUrl, $returnUrl);
        }
    }

    /**
     * @throws NotFoundHttpException
     */

    public function actionValidate(){
        $model = new  LoginForm();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionLogin()
    {

        try{

            if(!Yii::$app->user->isGuest){
                Yii::$app->getSession()->setFlash('error', Yii::t('app', "Вы уже авторизованы."));

                if(Yii::$app->request->isAjax)
                    return ['success' => $_SESSION[self::RedirectUrl] ?? '/'];
                else
                    return Yii::$app->response->redirect([$_SESSION[self::RedirectUrl] ?? '/']);

            }

            if (Yii::$app->request->isAjax ){
                Yii::$app->response->format = Response::FORMAT_JSON;
                $model = new  LoginForm();

               if($model->load(Yii::$app->request->post()) && $model->login()){
                   return ['success' => $_SESSION[self::RedirectUrl] ?? '/'];
               }else{
                   return ['errors' => $model->getErrors()];
               }

            }


                $this->rememberBackUrl(); //Запомним откуда пришел пользователь
              return $this->render('login', ['model' => new LoginForm(),]);

        }
        catch (Exception $e){
            Yii::error($e);

                if(Yii::$app->request->isAjax)
                    return json_encode(['success' => false, 'errors' => ['unknown' => $e->getMessage()]]);
                else
                    throw new \yii\web\NotFoundHttpException($e);

        }
    }


    /**
     * @throws \yii\db\Exception
     */
    public function actionSignup($client = null) //Регистрация
    {
        try {

            $model = new SignupForm();
            if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

            if ($client != null && false) {
                if (isset($attributes['email']) && User::find()->where(['email' => $attributes['email']])->exists()) {
                    Yii::$app->getSession()->setFlash('error', [
                        Yii::t('app', "Пользователь с такой электронной почтой как в {client} уже существует, но с ним не связан. Для начала войдите на сайт использую электронную почту, для того, что бы связать её.", ['client' => $client->getTitle()]),
                    ]);
                } else {
                    $attributes = $client->getUserAttributes();

                    $user = new User();
                    $user->login = $attributes['login'];
                    $user->email = $attributes['email'];
                    $transaction = $user->getDb()->beginTransaction();
                    try{
                        if ($user->save()) {
                            $auth = new Auth([
                                'user_id' => $user->id,
                                'source' => $client->getId(),
                                'source_id' => (string)$attributes['id'],
                            ]);
                            if ($auth->save()) {

                                $fullInfo = new FullInfo(['user_id' => $user->id]);

                                if ($fullInfo->save()) {
                                    $transaction->commit();

                                    Yii::$app->user->login($user);

                                    $url = isset($session[self::RedirectUrl]) ? $session->get(self::RedirectUrl) : '/';
                                    return json_encode(['success' => true, self::ReturnUrl => $url]);
                                }
                            }
                        }

                    }catch (\yii\db\Exception $e){
                        $transaction->rollBack(); //Отменили транзакцию
                        Yii::error($e, 'common'); //Записали лог об ошибке
                        Yii::$app->getSession()->setFlash('error', Yii::t('app', "Ошибка регистрации"));
                    }

                }

            }


            if ($model->load(Yii::$app->request->post()) && $model->signup()) {
                Yii::$app->getSession()->setFlash('success', Yii::t('app', "Регистрация прошла успешно!"));
               return $this->goBack();
            }

            return $this->render('signup', [
                'model' =>  $model,
            ]);
        }
        catch (Exception $e){
                Yii::error($e);
                if(Yii::$app->request->isAjax){
                    return json_encode(['error' => false, 'errors' => Yii::t('app', "Произошла ошибка работы сервера.")]);
                }else{
                    Yii::$app->getSession()->setFlash('error', Yii::t('app', $e->getMessage()));
                    return  $this->refresh();
                }

        }

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
     *  request-password-reset
     */
    public function actionRequestPasswordReset() //Оправляем токен на почту, для сброса пароля
    {

        try{
            $model = new PasswordResetRequestForm();
            if ($model->load(Yii::$app->request->post()) && $model->validate()) { //Проверяем данные на валидность
                if ($model->sendEmail()) { //Отправляем токен восстановления на почту
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Проверьте свою электронную почту для получения дальнейших инструкций.'));
                    return $this->goHome();
                }

                Yii::$app->session->setFlash('error', Yii::t('app', 'К сожалению, мы не можем сбросить пароль для указанного адреса электронной почты.'));
            }

            return $this->render('requestPasswordResetToken', [
                'model' => $model,
            ]);
        }catch (\Exception $e){
            Yii::error($e);
            throw new ServerErrorHttpException($e->getFile(). "\n". $e->getLine() . "\n" . "Произошла ошибка работы сервера.\n ". $e->getMessage());
        }
    }

    /**
     *Сбрасывает пароль.
     * Сюда прилетает пользователь по ссылки из элетронной почты. для сброса пароля
     * @param string $token
     * @return string|Response
     * @throws Exception
     */
    public function actionResetPassword(string $token): string|Response //По ссылке из почты с токеном для сброса пароля
    {
        try {
            $model = new ResetPasswordForm($token); //Если токен верный, то позьзователь сможет загрузить форму для отправки нового пароля

            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Пароль сохранен.'));
                return $this->redirect(Url::to(['auth/login']));
            }
            return $this->render('resetPassword', [
                'model' => $model,
            ]);

        } catch (\yii\base\InvalidArgumentException) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Истекло время жизни токена!'));
            return $this->redirect("/auth/request-password-reset");

        } catch (Exception $e){
            Yii::error($e);
            throw new ServerErrorHttpException(Yii::t('app', "Произошла ошибка работы сервера."));
        }



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
            Yii::$app->session->setFlash('success', Yii::t('app', 'Ваш адрес электронной почты успешно был подтвержден!'));
            return $this->refresh();
        }

        Yii::$app->session->setFlash('error', Yii::t('app', 'К сожалению, произошел сбой подтверждения. Мы уже об этом знаем, наши специалисты приступили к работе.'));
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
                Yii::$app->session->setFlash('success', Yii::t('app', 'Проверьте свою электронную почту для получения дальнейших инструкций.'));
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', Yii::t('app', 'К сожалению, мы не можем повторно отправить электронное письмо с подтверждением на указанный адрес электронной почты.'));
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }

}