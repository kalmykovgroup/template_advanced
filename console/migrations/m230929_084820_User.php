<?php

use yii\db\Migration;

/**
 * Class m230929_084820_User
 */
class m230929_084820_User extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'login' => $this->string(255)->Null(), //Имя, хранится здесь, что-бы не подгружить дополнительную таблицу с полной инфой
            'name' => $this->string(255)->Null(), //Имя, хранится здесь, что-бы не подгружить дополнительную таблицу с полной инфой
            'auth_key' => $this->string()->notNull(), //Сдесь хранится ключ, используемый для основанной на cookie аутентификации
            'access_token' => $this->string()->notNull(), //Когда требуется аутентифицировать пользователя только по секретному токену (например в RESTful приложениях, не сохраняющих состояние между запросами).
            'password_hash' => $this->string()->notNull(), //Обычный захешованный пароль
            'password_reset_token' => $this->string()->Null(), //Сдесь хранится пароль для сброса основного
            'verification_token' => $this->string()->Null(), //Сдесь хранится токен верефикации, он нужен
            'email' => $this->string()->Null(),
            'phone' => $this->string()->Null(),
            'status' => $this->smallInteger()->Null()->defaultValue(10), //10 - Активный, удален, заблоктрован ...
            'created_at' => $this->timestamp()->defaultExpression('NOW()'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE NOW()'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230929_084820_User cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230929_084820_User cannot be reverted.\n";

        return false;
    }
    */
}
