<?php

use yii\db\Migration;

/**
 * Class m231105_173004_category
 */
class m231105_173004_category extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable('category', [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer()->Null(),
            'name' => $this->string()->Null(),
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231105_173004_category cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231105_173004_category cannot be reverted.\n";

        return false;
    }
    */
}
