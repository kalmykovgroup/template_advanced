<?php

namespace common\models;

use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * LoginForm model
 *
 * @property-read User|null $_user
 ** @property string $login
 ** @property string $email
 * @property string $phone
 * @property string $password
 * @property bool $rememberMe
 * @property string $login_method
 */


class LoginForm extends Model
{
    public string $login = "";
    public string $email = "";
    public string $phone = "";
    public string $password = "";
    public bool $rememberMe = true;
    private null|User|ActiveRecord $_user = null;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {

        //Добавляем общие правила валидации
         return array_merge(
            ValidateForm::emailRules(),
            ValidateForm::phoneRules(),
            ValidateForm::loginRules(),
            ValidateForm::passwordRules(),

        [

            [['login', 'email', 'phone'], 'required' , 'message' => Yii::t('app', "{attribute} не может быть пустым!"), 'when' => function(){
                return empty($this->login) && empty($this->phone) && empty($this->email);

            }, 'whenClient' => "function (attribute, value) {
                    return $('#loginform-email').val() == '' && $('#loginform-phone').val() == '' && $('#loginform-login').val() == '' ;
                }"],

            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],

        ]);

    }


    /**
     * @throws Exception
     */
    public function isCheckFieldsLoginEmailPhone(): void
    {
         if(!empty($this->phone)){ //Если не пустое поле телефон
            if(!$this->getUser('phone', $this->phone)) {
                $this->addError('phone', Yii::t('app', 'Пользователь '.$this->phone.' не найден!'));
            }
        }
        else if(!empty($this->email)){
            $validator = new \yii\validators\EmailValidator();
            $this->email =  mb_strtolower($this->email); //Приведем к нижнему реестру

            if ($validator->validate($this->email, $error)) {  // и проверяем что данные являются праельным эмейл адресом
                if(!$this->getUser('email', $this->email)) {
                    $this->addError('email', Yii::t('app', 'Пользователь '.$this->email.' не найден!'));
                }
            }else{
                $this->addError('email', Yii::t('app', 'Не являеться правильным Email адресом'));
            }

        }else{
            throw new \InvalidArgumentException(Yii::t('app', "Аргументы пусты!"));
        }

    }


    /**
     * @throws Exception
     */
    public function validatePassword($attribute): void
    {
        $this->isCheckFieldsLoginEmailPhone();
        if (!$this->hasErrors()) {
            if (!$this->_user->validatePassword($this->password)) {
                $this->addError($attribute, 'Пароль не подходит');
            }
        }
    }


    /**
     */
    public function login(): bool
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->_user, $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }


    /**
     * @throws Exception
     */
    public function getUser($field, $value): User|array|ActiveRecord|null
    {
        if (!$this->_user) {
             $this->_user = User::findByUsername([$field => $value]);
        }

        $this->_user?->checkStatusUser();

        return $this->_user;

    }
}
