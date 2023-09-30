<?php

use yii\db\Migration;

/**
 * Class m230929_092139_AddRole
 */
class m230929_092139_AddRole extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $rbac = \Yii::$app->authManager;

        $guest = $rbac->createRole('guest');
        $guest->description = 'Гость';
        $rbac->add($guest);

        $user = $rbac->createRole('user');
        $user->description = 'Может испльзовать пользовательский интерфейс и ничего больше';
        $rbac->add($user);

        $manager = $rbac->createRole('manager');
        $manager->description = 'Может упровлять обьектами в базе данных, но не для пользователя';
        $rbac->add($manager);

        $admin = $rbac->createRole('admin');
        $admin->description = 'Может делать что угодно, включая управление пользователями';
        $rbac->add($admin);

        $rbac->addChild($admin, $manager);
        $rbac->addChild($manager, $user);
        $rbac->addChild($user, $guest);

        $connection = Yii::$app->db;

        //Меняем varchar на int и потом связываем с таблицей user что-бы при удалении пользователя - удалялись и все записи связанные с ним
        $connection->createCommand("ALTER TABLE `auth_assignment` CHANGE `user_id` `user_id` INT NOT NULL")->execute();
        $connection->createCommand("ALTER TABLE `auth_assignment` ADD CONSTRAINT `fk-auth_assignment-user_id-user-id` FOREIGN KEY (`user_id`) REFERENCES `user`(`id`) ON DELETE CASCADE ON UPDATE CASCADE")->execute();

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230929_092139_AddRole cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230929_092139_AddRole cannot be reverted.\n";

        return false;
    }
    */
}
