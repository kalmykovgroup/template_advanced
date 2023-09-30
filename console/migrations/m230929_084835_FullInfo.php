<?php

use yii\db\Migration;

/**
 * Class m230929_084835_FullInfo
 */
class m230929_084835_FullInfo extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('full_info', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(), //Ссылка на пользователя
            'address_id' => $this->integer()->Null(), // Индекс активного адреса
            'last_name' => $this->string(255)->Null(), //Фамилия
            'patronymic' => $this->string(255)->Null(), //Отчество
            'date_of_birth' => $this->date()->Null(), //Дата рождения
            'gender' => "ENUM('female', 'male')", // female - женский.
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE NOW()'),
        ]);
        //Связываем User and UserInformation
        $this->addForeignKey('fk-full_info-user_id-user-id', 'full_info', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230929_084835_FullInfo cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230929_084835_FullInfo cannot be reverted.\n";

        return false;
    }
    */
}
