<?php

use yii\db\Migration;

/**
 * Class m231019_150554_social_networks
 */
class m231019_150554_social_networks extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable('{{%social_networks}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'clientId' => $this->string()->Null(),
            'clientSecret' => $this->string()->Null(),
            'username' => $this->string()->Null(),
            'password' => $this->string()->Null(),
            'src' => $this->string()->Null(),
            'status' => "ENUM('0', '1')  NOT NULL DEFAULT '0'",
            'created_at' => $this->timestamp()->defaultExpression('NOW()'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE NOW()'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231019_150554_social_networks cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231019_150554_social_networks cannot be reverted.\n";

        return false;
    }
    */
}
