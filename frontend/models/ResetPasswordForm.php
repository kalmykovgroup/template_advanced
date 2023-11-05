<?php

namespace frontend\models;

use yii\base\Exception;
use yii\base\InvalidArgumentException;
use yii\base\Model;
use Yii;
use common\models\User;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $password;

    /**
     * @var User|null
     */
    private ?User $_user;


    /**
     * Creates a form model given a token.
     *
     * @param string $token
     * @param array $config пары имя-значение, которые будут использоваться для инициализации свойств объекта
     * @throws InvalidArgumentException if token is empty or not valid
     */
    public function __construct($token, array $config = [])
    {
        if (empty($token) || !is_string($token)) {
            Yii::error('Токен сброса пароля не может быть пустым!');
            throw new InvalidArgumentException();
        }
        $this->_user = User::findByPasswordResetToken($token);
        if (!$this->_user) {
            throw new InvalidArgumentException();
        }
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['password', 'trim'],
            [['password'], 'required', 'message' => "{attribute} не может быть пустым!"] ,
            ['password', 'string', 'min' => 6, 'message' => 'мин. 6 симв.'],

            ['password', 'match', 'pattern' =>  '/^(?=.*[a-z])/','message' => "Пароль должен содержать буквы"],
            ['password', 'match', 'pattern' =>  '/^(?=.*[0-9])/','message' => "Пароль должен содержать цыфры"],
        ];
    }

    /**
     * Resets password.
     *
     * @return bool if password was reset.
     * @throws Exception
     */
    public function resetPassword(): bool
    {
        $user = $this->_user;
        $user->setPassword($this->password); //Захешировали пароль
        $user->removePasswordResetToken();  //Удаляем токен востановления, так как он нужен только в момент востановления (имеет ограниченное время работы)
        $user->generateAuthKey();  //Генерируем новый auth токен (ключ, используемый для основанной на cookie аутентификации)

        return $user->save(false);
    }
}
