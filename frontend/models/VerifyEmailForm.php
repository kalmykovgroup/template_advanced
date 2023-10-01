<?php

namespace frontend\models;

use common\models\User;
use yii\base\InvalidArgumentException;
use yii\base\Model;

class VerifyEmailForm extends Model
{

    public string $token;


    private ?User $_user;


    /**
     * Creates a form model with given token.
     *
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @throws InvalidArgumentException if token is empty or not valid
     */
    public function __construct($token, array $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidArgumentException('Токен проверки электронной почты не может быть пустым.');
        }
        $this->_user = User::findByVerificationToken($token);
        if (!$this->_user) {
            throw new InvalidArgumentException('Неверный токен проверки электронной почты.');
        }
        parent::__construct($config);
    }

    /**
     * Verify email
     *
     * @return bool the saved model or null if saving fails
     */
    public function verifyEmail(): bool
    {
        $user = $this->_user;
        $user->email_status = User::EMAIL_ACTIVE;
        return $user->save(false);
    }
}
