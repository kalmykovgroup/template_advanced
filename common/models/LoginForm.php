<?php

namespace common\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user
 *
 */
class LoginForm extends Model
{
    public  $username;
    public  $phone;
    public  $password;
    public bool $rememberMe = true;
    public string $login_method;
    private $_user = false;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            [['username', 'phone'], 'required','message' => "Поле не может быть пустым!",  'when' => function(){
                if(empty($this->username) && empty($this->password)){
                    $this->addErrors(['phone', 'username']);
                }
            }],// обрезает пробелы вокруг
            [['password'], 'required', 'message' => "Поле не может быть пустым!"] ,
            [['username', 'password', 'phone'], 'trim'],// обрезает пробелы вокруг
            [['username'], 'string', 'max' => 255],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength'], 'message' => 'мин. '.Yii::$app->params['user.passwordMinLength'].' сим.'],
            ['phone',  function(){
               $this->phone = preg_replace('/[^0-9]/', '', $this->phone);
            }],
            ['phone', 'string', 'length' => [11, 20], 'message' => 'Не верный формат'],

            ['rememberMe', 'boolean'],

            ['username', 'isCheckPairUsernamePhone', 'skipOnEmpty' => false, 'skipOnError' => false, ],

            ['password', 'validatePassword'],
        ];
    }

    public function isCheckPairUsernamePhone($attribute): void
    {
         if(!empty($this->phone)){ //Если не пустое поле телефон
            $this->login_method = 'phone';
            if(!$this->getUser()) {
                $this->addError('phone', 'Пользователь не найден!');
            }
        }
        else{
            $validator = new \yii\validators\EmailValidator();

            if ($validator->validate($this->username, $error)) {  // и проверяем что данные являются праельным эмейл адресом
                $this->login_method = 'email'; //Пробуем найти по Емайлу
            }else{
                $this->login_method = 'login'; //Пробуем найти по логину
            }
            if(!$this->getUser()){
                $this->addError('username', 'Пользователь не найден!');
            }

        }

    }

    public function isEmail(): bool
    {
        //создаем экземпляр модели

        return false;
    }

    public function validatePassword($attribute, $params): void
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Пароль не подходит');
            }
        }
    }


    public function login(): bool
    {
        if ($this->validate()) {
            $session = Yii::$app->session;
            $session->open();

            if($this->login_method == 'phone'){
                $session->set('username', $this->getUser()->phone);
            }else if($this->login_method == 'email'){
                $session->set('username', $this->getUser()->email);
            }else{
                $session->set('username', $this->getUser()->login);
            }


            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }


    public function getUser()
    {
        if (!$this->_user) {
            if($this->login_method == 'phone'){
                $this->_user = User::findByUsername(['phone' => $this->phone]);
            }else if($this->login_method == 'email'){
                $this->_user = User::findByUsername(['email' => $this->username]);
            }else{
                $this->_user = User::findByUsername(['login' => $this->username]);
            }
        }

        return $this->_user;
    }
}
