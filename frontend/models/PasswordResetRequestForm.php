<?php

namespace frontend\models;

use Yii;
use yii\base\Exception;
use yii\base\Model;
use common\models\User;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Password reset request form

 ** @property string $email
 */


class PasswordResetRequestForm extends Model
{
    public $email;
    private null|User|ActiveRecord $_user = null;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['email', 'trim'],
            ['email', 'required', 'message' => "Поле не может быть пустым!"],
            ['email', 'email', 'message' => "Email не валидный!"],
            ['email',  function(){
                if(!$this->getUser()){
                    $this->addError('phone', 'Пользователь ' . $this->email . 'не найден. Возможно Email не был подтвержден после регистрации');
                }
            }],
        ];
    }


    public function getUser(): User|array|ActiveRecord|null
    {
        if (!$this->_user) {
            $this->_user = User::findByUsername(['email' => $this->email, 'status' => User::EMAIL_ACTIVE]);

            if(!$this->_user){

                //Если не нашли, пробуем искать в неподтвержденных
                //Если что-то нашли, есть два сценария
                //Если аккаун один, то возвращаем его, иначе не понятно кого возращать - поэтому ничего не делаем!

                 if(User::find()->where(['email' => $this->email])->count() == 1){
                     $this->_user = User::findByUsername(['email' => $this->email]);
                 }
            }

        }

        $this->_user?->checkStatusUser();

        return $this->_user;
    }

    /**
     * Отправляет электронное письмо со ссылкой для сброса пароля.
     *
     * @return bool whether the email was sent
     * @throws Exception
     */
    public function sendEmail(): bool
    {
        /* @var $user User */
        $user = $this->getUser();

        if (!$user){
            Yii::error("При повторной проверки имейл уже не подходил (пользователь не найден!");
            throw new NotFoundHttpException();
        }

        if (!User::isPasswordResetTokenValid($user->password_reset_token)) { //Если предыдущий токен уже не валиден
            $user->generatePasswordResetToken();  //Создаем новый
            if (!$user->save()) { //Если не получилось сохранить
                Yii::error("Не получилось сохранить в базу");
                throw new ServerErrorHttpException();
            }
        }

        return Yii::$app //Отправляем на почту токен восстановления
            ->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Password reset for ' . Yii::$app->name)
            ->send();
    }
}
