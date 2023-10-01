<?php

use yii\db\Migration;

/**
 * Class m230929_092131_Comments
 */
class m230929_092131_Comments extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // https://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%comments}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'text' => $this->text()->Null(),
            'created_at' => $this->timestamp()->defaultExpression('NOW()'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE NOW()'),
        ], $tableOptions);

        //Связываем Address and Comments
        $this->addForeignKey('fk-address-comment_id-comments-id', 'address', 'comment_id', 'comments', 'id', 'CASCADE', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%comments}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230929_092131_Comments cannot be reverted.\n";

        return false;
    }
    */
}
