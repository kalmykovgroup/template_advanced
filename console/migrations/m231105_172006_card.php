<?php

use yii\db\Migration;

/**
 * Class m231105_172006_card
 */
class m231105_172006_card extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {

          $this->createTable('card', [
              'id' => $this->primaryKey(),
              'product_id' => $this->integer()->Null(),
              'user_id' => $this->integer()->Null(),
              'qty' => $this->integer()->Null(),
          ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231105_172006_card cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231105_172006_card cannot be reverted.\n";

        return false;
    }
    */
}
