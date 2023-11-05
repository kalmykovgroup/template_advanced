<?php

use yii\db\Migration;

/**
 * Class m230929_092108_Address
 */
class m230929_092108_Address extends Migration
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

        $this->createTable('{{%address}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'comment_id' => $this->integer()->Null(), //Путь к коментарию, если он есть.
            'street' => $this->integer()->notNull(), //Улица
            'house' => $this->string()->notNull(),      //Дом
            'apartment' => $this->string()->Null(),  //Квартира
            'entrance' => $this->string()->Null(),   //Подьезд
            'intercom' => $this->string()->Null(),   //Домофон
        ], $tableOptions);

        //Связываем Address and User
        $this->addForeignKey('fk-address-user_id-user-id', 'address', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
        //Связываем Address and Comments
        $this->addForeignKey('fk-address-comment_id-comments-id', 'address', 'comment_id', 'comments', 'id', 'CASCADE', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%address}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230929_092108_Address cannot be reverted.\n";

        return false;
    }
    */
}
