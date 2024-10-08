<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var LoginForm $model */

use common\models\LoginForm;
use frontend\assets\Auth\AuthAsset;
use frontend\assets\Auth\LoginAsset;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;

AuthAsset::register($this);
LoginAsset::register($this);
?>

<div class="site-login">
    <div class="centerBlockForm">


        <div class="titleBlock">Авторизация</div>

        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'action' => '/auth/login',
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}",
                'options' => ['class' => 'form-group'],
            ],
        ]); ?>

        <div class="methodSelection methodSelectionLogin">
            <a href="#" class="labelEmail active"  data-btn="email"><span class="text">Email</span></a>
            <a href="#" class="labelPhone" data-btn="phone"> <span class="text">Телефон</span> </a>
        </div>


        <div class="blockFields">

            <?= $form->field($model, 'phone')->textInput(['placeholder'=>'Phone',])->label(false)?>

            <?= $form->field($model, 'email')->textInput(['placeholder'=>'Email',])->label(false)?>

        </div>


        <?= $form->field($model, 'password', [
            'labelOptions' => [ 'class' => 'visually-hidden' ],

        ])->passwordInput([
            'placeholder'=>'Password',
        ])?>

        <div id="bigErrorMessage"></div>

        <div class="form-group">
            <?= $form->field($model, 'rememberMe')->checkbox([
                'checked' => true,
                'label'=> 'Запомнить'
            ]) ?>

            <div class="blockBtn">
                <button type="submit">Войти</button>
            </div>
        </div>
        <div class="bottom_text_reg">Нет Аккаунта? пройдите &nbsp;<a href="<?=Url::to(['auth/signup']);?>">Регистрацию</a></div>

        <div class="recoverPassword">
            <a href="<?=Url::to(['auth/request-password-reset'])?>">Забыл пароль</a>
        </div>


        <?php ActiveForm::end(); ?>

        <div class="blockServices">

            <div class="title">Войти с помощью соц. сетей:</div>

            <div class="aServices">


                <a href="/auth/auth?authclient=vkontakte"  class="service">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         aria-label="VK" role="img"
                         viewBox="0 0 512 512"><rect
                                width="512" height="512"
                                rx="15%"
                                fill="#5281b8"/><path fill="#ffffff" d="M274 363c5-1 14-3 14-15 0 0-1-30 13-34s32 29 51 42c14 9 25 8 25 8l51-1s26-2 14-23c-1-2-9-15-39-42-31-30-26-25 11-76 23-31 33-50 30-57-4-7-20-6-20-6h-57c-6 0-9 1-12 6 0 0-9 25-21 45-25 43-35 45-40 42-9-5-7-24-7-37 0-45 7-61-13-65-13-2-59-4-73 3-7 4-11 11-8 12 3 0 12 1 17 7 8 13 9 75-2 81-15 11-53-62-62-86-2-6-5-7-12-9H79c-6 0-15 1-11 13 27 56 83 193 184 192z"/></svg>
                </a>
                <a href="/auth/auth?authclient=google"  class="service">
                    <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 48 48" width="48px" height="48px">
                        <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"/><path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"/><path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"/><path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"/>
                    </svg>
                </a>
                <a href="#"  class="service">
                    <svg xmlns="http://www.w3.org/2000/svg" height="800" width="1200" viewBox="-10.2615 -6.31 88.933 37.86"><path d="M7.93 25.01v-8.02L13.82 0h-2.5l-4.6 13.57L2.73 2.35H0L5.42 16.9v8.11z" fill="red"/><path d="M18.77 21.88c-.45.69-1.3 1.26-2.16 1.26-1.27 0-1.87-1.2-1.87-3.04 0-2.06.67-3.42 3.74-3.42h.29zm2.44-.48v-7.51c0-3.87-1.56-5.07-4.38-5.07-1.61 0-3.07.66-3.8 1.26l.47 2.19c.83-.73 1.91-1.39 3.17-1.39 1.4 0 2.1.88 2.1 2.98v.95h-.35c-4.47 0-6.22 2.15-6.22 5.58 0 3.14 1.56 4.85 3.84 4.85 1.4 0 2.28-.61 2.98-1.49h.16c.03.41.13.92.22 1.26h2.09c-.12-.76-.28-2.09-.28-3.61zm5.55-8.78c.57-.79 1.45-1.58 2.5-1.58.95 0 1.49.41 1.49 1.61v12.36h2.5V12.43c0-2.44-1.11-3.61-3.2-3.61-1.49 0-2.69.98-3.14 1.58h-.15V9.01h-2.48v16h2.48zm13.78 12.62c1.3 0 2.19-.61 2.89-1.49h.16l.19 1.26h1.84V2.35h-2.48v7.07c-.47-.35-1.26-.6-2.02-.6-3.21 0-5.43 2.88-5.43 8.81 0 4.94 1.72 7.61 4.85 7.61zm2.6-3.33c-.41.63-1.07 1.23-2.31 1.23-1.81 0-2.5-2.28-2.5-6.02 0-3.26.95-6.27 2.94-6.27.83 0 1.37.25 1.87.76zm14.11 2.03l-.57-1.9c-.63.44-1.65 1.07-3.04 1.07-1.97 0-2.98-1.9-2.98-5.51h6.65v-1.37c0-5.42-1.74-7.41-4.4-7.41-3.39 0-4.82 3.74-4.82 8.87 0 4.92 2.03 7.55 5.32 7.55 1.59 0 2.83-.51 3.84-1.3zm-4.34-13.09c1.42 0 1.87 1.99 1.87 4.72h-4.09c.16-2.85.7-4.72 2.22-4.72zm12.74-1.81l-2.19 5.8-2.06-5.8h-2.56l3.01 7.7-3.33 8.27h2.44l2.41-6.59 2.47 6.59h2.57l-3.33-8.49 2.98-7.48z"/>
                    </svg>
                </a>
                <a href="#" class="service">
                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 24C0 10.7452 10.7452 0 24 0C37.2548 0 48 10.7452 48 24C48 37.2548 37.2548 48 24 48C10.7452 48 0 37.2548 0 24Z" fill="#005FF9"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M10 23.9603C10 16.2625 16.2802 10 23.9998 10C31.7194 10 37.9997 16.2625 37.9997 23.9603C37.9997 25.0508 37.9087 25.9336 37.7048 26.8197L37.7018 26.8344C37.7013 26.837 37.6001 27.2455 37.5442 27.4237C37.1982 28.5272 36.5718 29.4463 35.7325 30.0815C34.9186 30.6977 33.9094 31.0371 32.891 31.0371C32.7652 31.0371 32.6385 31.032 32.5144 31.0221C31.0682 30.9063 29.7966 30.1474 29.0168 28.9373C27.6757 30.282 25.8964 31.0218 23.9998 31.0218C20.0951 31.0218 16.9184 27.854 16.9184 23.9603C16.9184 20.0666 20.0951 16.8989 23.9998 16.8989C27.9045 16.8989 31.0812 20.0666 31.0812 23.9603V26.2366C31.087 27.5648 31.9808 28.1077 32.7444 28.1691C33.5041 28.2276 34.5152 27.7863 34.8674 26.3461C35.0411 25.5562 35.1294 24.7533 35.1294 23.9603C35.1294 17.8407 30.1367 12.8621 23.9998 12.8621C17.8629 12.8621 12.8703 17.8407 12.8703 23.9603C12.8703 30.0798 17.8629 35.0585 23.9998 35.0585C26.136 35.0585 28.2136 34.4501 30.0079 33.2991L30.0401 33.2784L31.9261 35.465L31.8856 35.4926C29.5549 37.0811 26.828 37.9207 23.9998 37.9207C16.2802 37.9207 10 31.6581 10 23.9603ZM23.9998 28.1596C26.3218 28.1596 28.2109 26.2758 28.2109 23.9603C28.2109 21.6448 26.3218 19.7612 23.9998 19.7612C21.6777 19.7612 19.7887 21.6448 19.7887 23.9603C19.7887 26.2758 21.6777 28.1596 23.9998 28.1596Z" fill="#FF9E00"/>
                    </svg>

                </a>
                <a href="#" class="service">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 128 128"><g fill="#181616"><path fill-rule="evenodd" clip-rule="evenodd" d="M64 1.512c-23.493 0-42.545 19.047-42.545 42.545 0 18.797 12.19 34.745 29.095 40.37 2.126.394 2.907-.923 2.907-2.047 0-1.014-.04-4.366-.058-7.92-11.837 2.573-14.334-5.02-14.334-5.02-1.935-4.918-4.724-6.226-4.724-6.226-3.86-2.64.29-2.586.29-2.586 4.273.3 6.523 4.385 6.523 4.385 3.794 6.504 9.953 4.623 12.38 3.536.383-2.75 1.485-4.628 2.702-5.69-9.45-1.075-19.384-4.724-19.384-21.026 0-4.645 1.662-8.44 4.384-11.42-.442-1.072-1.898-5.4.412-11.26 0 0 3.572-1.142 11.7 4.363 3.395-.943 7.035-1.416 10.65-1.432 3.616.017 7.258.49 10.658 1.432 8.12-5.504 11.688-4.362 11.688-4.362 2.316 5.86.86 10.187.418 11.26 2.728 2.978 4.378 6.774 4.378 11.42 0 16.34-9.953 19.938-19.427 20.99 1.526 1.32 2.886 3.91 2.886 7.88 0 5.692-.048 10.273-.048 11.674 0 1.13.766 2.458 2.922 2.04 16.896-5.632 29.07-21.574 29.07-40.365C106.545 20.56 87.497 1.512 64 1.512z"/><path d="M37.57 62.596c-.095.212-.428.275-.73.13-.31-.14-.482-.427-.382-.64.09-.216.424-.277.733-.132.31.14.486.43.38.642zm-.524-.388M39.293 64.52c-.203.187-.6.1-.87-.198-.278-.297-.33-.694-.124-.884.208-.188.593-.1.87.197.28.3.335.693.123.884zm-.406-.437M40.97 66.968c-.26.182-.687.012-.95-.367-.262-.377-.262-.83.005-1.013.264-.182.684-.018.95.357.262.385.262.84-.005 1.024zm0 0M43.268 69.336c-.233.257-.73.188-1.093-.163-.372-.343-.475-.83-.242-1.087.237-.257.736-.185 1.102.163.37.342.482.83.233 1.086zm0 0M46.44 70.71c-.104.334-.582.485-1.064.344-.482-.146-.796-.536-.7-.872.1-.336.582-.493 1.067-.342.48.144.795.53.696.87zm0 0M49.92 70.965c.013.35-.396.642-.902.648-.508.012-.92-.272-.926-.618 0-.354.4-.642.908-.65.506-.01.92.272.92.62zm0 0M53.16 70.414c.06.342-.29.694-.793.787-.494.092-.95-.12-1.014-.46-.06-.35.297-.7.79-.792.503-.088.953.118 1.017.466zm0 0"/></g><g fill="#100E0F"><path d="M24.855 108.302h-10.7a.5.5 0 0 0-.5.5v5.232a.5.5 0 0 0 .5.5h4.173v6.5s-.937.32-3.53.32c-3.056 0-7.327-1.116-7.327-10.508 0-9.393 4.448-10.63 8.624-10.63 3.614 0 5.17.636 6.162.943.31.094.6-.216.6-.492l1.193-5.055a.468.468 0 0 0-.192-.39c-.403-.288-2.857-1.66-9.058-1.66-7.144 0-14.472 3.038-14.472 17.65 0 14.61 8.39 16.787 15.46 16.787 5.854 0 9.405-2.502 9.405-2.502.146-.08.162-.285.162-.38v-16.316a.5.5 0 0 0-.5-.5zM79.506 94.81H73.48a.5.5 0 0 0-.498.503l.002 11.644h-9.392V95.313a.5.5 0 0 0-.497-.503H57.07a.5.5 0 0 0-.498.503v31.53c0 .277.224.503.498.503h6.025a.5.5 0 0 0 .497-.504v-13.486h9.392l-.016 13.486c0 .278.224.504.5.504h6.038a.5.5 0 0 0 .497-.504v-31.53c0-.278-.22-.502-.497-.502zM32.34 95.527c-2.144 0-3.884 1.753-3.884 3.923 0 2.167 1.74 3.925 3.884 3.925 2.146 0 3.885-1.758 3.885-3.925 0-2.17-1.74-3.923-3.885-3.923zM35.296 105.135H29.29c-.276 0-.522.284-.522.56v20.852c0 .613.382.795.876.795h5.41c.595 0 .74-.292.74-.805v-6.346-14.553a.5.5 0 0 0-.498-.502zM102.902 105.182h-5.98a.5.5 0 0 0-.496.504v15.46s-1.52 1.11-3.675 1.11-2.727-.977-2.727-3.088v-13.482a.5.5 0 0 0-.497-.504h-6.068a.502.502 0 0 0-.498.504v14.502c0 6.27 3.495 7.804 8.302 7.804 3.944 0 7.124-2.18 7.124-2.18s.15 1.15.22 1.285c.07.136.247.273.44.273l3.86-.017a.502.502 0 0 0 .5-.504l-.003-21.166a.504.504 0 0 0-.5-.502zM119.244 104.474c-3.396 0-5.706 1.515-5.706 1.515V95.312a.5.5 0 0 0-.497-.503H107a.5.5 0 0 0-.5.503v31.53a.5.5 0 0 0 .5.503h4.192c.19 0 .332-.097.437-.268.103-.17.254-1.454.254-1.454s2.47 2.34 7.148 2.34c5.49 0 8.64-2.784 8.64-12.502s-5.03-10.988-8.428-10.988zm-2.36 17.764c-2.073-.063-3.48-1.004-3.48-1.004v-9.985s1.388-.85 3.09-1.004c2.153-.193 4.228.458 4.228 5.594 0 5.417-.935 6.486-3.837 6.398zM53.195 122.12c-.263 0-.937.107-1.63.107-2.22 0-2.973-1.032-2.973-2.368v-8.866h4.52a.5.5 0 0 0 .5-.504v-4.856a.5.5 0 0 0-.5-.502h-4.52l-.007-5.97c0-.227-.116-.34-.378-.34h-6.16c-.238 0-.367.106-.367.335v6.17s-3.087.745-3.295.805a.5.5 0 0 0-.36.48v3.877a.5.5 0 0 0 .497.503h3.158v9.328c0 6.93 4.86 7.61 8.14 7.61 1.497 0 3.29-.48 3.586-.59.18-.067.283-.252.283-.453l.004-4.265a.51.51 0 0 0-.5-.502z"/></g></svg>
                </a>

            </div>

        </div>
    </div>

</div>