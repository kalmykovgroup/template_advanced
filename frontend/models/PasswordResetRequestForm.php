<?php

namespace frontend\models;

use Yii;
use yii\base\Exception;
use yii\base\Model;
use common\models\User;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;


    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\common\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'There is no user with this email address.'
            ],
        ];
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
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if (!$user)  return false;

        if (!User::isPasswordResetTokenValid($user->password_reset_token)) { //Если предыдущий токен уже не валиден
            $user->generatePasswordResetToken();  //Создаем новый
            if (!$user->save()) { //Если не получилось сохранить
                return false;
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
