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
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'comment_id' => $this->integer()->Null(), //Путь к коментарию, если он есть.
            'street' => $this->integer()->notNull(), //Улица
            'house' => $this->string()->notNull(),      //Дом
            'apartment' => $this->string()->Null(),  //Квартира
            'entrance' => $this->string()->Null(),   //Подьезд
            'intercom' => $this->string()->Null(),   //Домофон
        ]);

        //Связываем Address and User
        $this->addForeignKey('fk-address-user_id-user-id', 'address', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230929_092108_Address cannot be reverted.\n";

        return false;
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
