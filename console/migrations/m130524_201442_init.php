<?php

use common\models\User;
use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // https://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'login' => $this->string(255)->Null(), //Имя, хранится здесь, что-бы не подгружить дополнительную таблицу с полной инфой
            'name' => $this->string(255)->Null(), //Имя, хранится здесь, что-бы не подгружить дополнительную таблицу с полной инфой
            'auth_key' => $this->string(255)->notNull(), //Сдесь хранится ключ, используемый для основанной на cookie аутентификации
            'access_token' => $this->string(255)->notNull(), //Требуется только тогда, когда нужно аутентифицировать пользователя только по секретному токену (например в RESTful приложениях, не сохраняющих состояние между запросами).
            'password_hash' => $this->string(255)->notNull(), //Обычный захешованный пароль
            'password_reset_token' => $this->string(255)->Null(), //Сдесь хранится ключ имеющий время работы, для сброса пароля (передаеться пользователю как ссылка для сброса)
            'verification_token' => $this->string(255)->Null(), //Сдесь хранится токен верефикации, он нужен для подтверждения электронной почты
            'email' => $this->string(255)->Null(),
            'phone' => $this->string(255)->Null(),
            'status' => $this->smallInteger()->Null()->defaultValue(User::STATUS_ACTIVE), //10 - Активный, удален, заблоктрован ...
            'email_status' => "ENUM('0', '1')  NOT NULL DEFAULT '".User::EMAIL_INACTIVE."'",
            'phone_status' => "ENUM('0', '1')  NOT NULL DEFAULT '".User::PHONE_INACTIVE."'",
            'created_at' => $this->timestamp()->defaultExpression('NOW()'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE NOW()'),
        ],$tableOptions);



    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
