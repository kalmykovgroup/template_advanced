<?php

use yii\db\Migration;

/**
 * Class m231019_172120_auth
 */
class m231019_172120_auth extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {

        $this->createTable('{{%auth}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(), //Ссылка на пользователя
            'source' => $this->string()->notNull(),
            'source_id' => $this->string()->notNull(),
        ]);

        //Связываем auth and User
        $this->addForeignKey('fk-auth-user_id-user-id', 'auth', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231019_172120_auth cannot be reverted.\n";

        return false;
    }


}
