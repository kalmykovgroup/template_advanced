<?php

namespace common\models;

use Yii;
use yii\base\Exception;
use yii\base\InvalidArgumentException;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 *
 * @property integer $id
 * @property string $name
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $phone
 * @property string $login
 * @property string $auth_key
 * @property string $access_token
 * @property integer $status
 * @property integer $email_status
 * @property integer $phone_status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property Auth $auth
 */
class User extends ActiveRecord  implements IdentityInterface
{
    const STATUS_DELETED = 0; //Пользователь был удален
    const STATUS_BLOCKED = 8; //Пользователь заблокирован кретически, за нарушения правил сервиса
    const STATUS_INACTIVE = 9; //Пользователь заблокирован но не кретически
    const STATUS_ACTIVE = 10;
    const EMAIL_INACTIVE = 0;
    const EMAIL_ACTIVE = 1;
    const PHONE_INACTIVE = 0;
    const PHONE_ACTIVE = 1;



    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */

    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                // если вместо метки времени UNIX используется datetime:
                // 'value' => new Expression('NOW()'),
            ],
        ];
    }



    public function getAuth(): ActiveQuery
    {
        return $this->hasOne(Auth::class, ['user_id' => 'id']);
    }
    public function getFullInfo(): ActiveQuery
    {
        return $this->hasOne(FullInfo::class, ['user_id' => 'id']);
    }

    /**
     * @throws Exception
     */
    public function beforeSave($insert): bool
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) { //Если запись новая
                $this->generateAuthKey();
                $this->generateAccessToken();
            }
            return true;
        }
        return false;
    }



    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],

            ['email_status', 'default', 'value' => self::EMAIL_INACTIVE],
            ['email_status', 'in', 'range' => [self::EMAIL_INACTIVE, self::EMAIL_ACTIVE]],

            ['phone_status', 'default', 'value' => self::PHONE_INACTIVE],
            ['phone_status', 'in', 'range' => [self::PHONE_INACTIVE, self::PHONE_ACTIVE]],
        ];
    }

    /**
     * @throws Exception
     */
    public function checkStatusUser(): bool
    {
        if(!empty($this->status)){
                if($this->status == User::STATUS_BLOCKED){
                    throw new Exception("Пользователь заблокирован за нарушение правил сервиса!", 423);
                }else if($this->status == User::STATUS_INACTIVE){
                    throw new Exception("Пользователь не активный! Обратитесь к администратору", 302);
                }
                else if($this->status == User::STATUS_DELETED){
                    throw new Exception("Пользователь был удален!", 410);
                }
           return true;
        }
        Yii::error( "Ошибка, возможно вызов функции у пустого юзера или проблема со статусом: " . $this->id);
        return false;

    }


    public function fields(): array
    {
        $fields = parent::fields();

        // удаляем небезопасные поля
        unset($fields['auth_key'], $fields['password_hash'], $fields['access_token']);
        return $fields;
    }

    public function getRole()
    {
        return array_values(Yii::$app->authManager->getRolesByUser($this->id))[0];
    }




    public static function findIdentity($id): User|IdentityInterface|null
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @throws Exception
     */
    public function generateAccessToken(): void
    {
        $this->access_token = Yii::$app->security->generateRandomString();
    }

    /**
     * {@inheritdoc}
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null): ?IdentityInterface
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }



    /**
     * Finds user by username
     *
     * @param $pair
     * @return ActiveRecord|array
     */
    public static function findByUsername($pair): null|ActiveRecord
    {
        return static::find()->where($pair)->andWhere(['status' => self::STATUS_ACTIVE])->one();
    }

    /**
     * Находит пользователя по токену сброса пароля
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken(string $token): null|static
    {
        if (!static::isPasswordResetTokenValid($token)) {
            throw new InvalidArgumentException('Истекло время жизни токена!');
        }

        return static::findOne([
            'password_reset_token' => $token,
        ]);
    }
    /**
     * Определяет, действителен ли токен сброса пароля
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid(string $token): bool
    {
        if (empty($token)){
            Yii::error('Токен сброса пароля не может быть пустым!');
            throw new InvalidArgumentException();
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1); //Выризаем строку с временем жизни токена
        $expire = Yii::$app->params['user.passwordResetTokenExpire']; //Берем общее время жизни токена
        return $timestamp + $expire >= time(); //Проверяем не закончилось ли время
    }


    /**
     * Находит пользователя по подтверждающему электронному токену
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken(string $token): null|static
    {
        return static::findOne([
            'verification_token' => $token,
            'email_status' => self::EMAIL_INACTIVE
        ]);
    }


    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey(): ?string
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey): ?bool
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword(string $password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Генерирует хэш пароля из password и устанавливает его в соответствии с моделью
     *
     * @param string $password
     * @throws Exception
     */
    public function setPassword(string $password): void
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     * @throws Exception
     */
    public function generateAuthKey(): void
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     * @throws Exception
     */
    public function generatePasswordResetToken(): void
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     * @throws Exception
     */
    public function generateEmailVerificationToken(): void
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken(): void
    {
        $this->password_reset_token = null;
    }
}
