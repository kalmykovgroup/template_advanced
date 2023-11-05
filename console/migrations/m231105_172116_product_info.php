<?php

use yii\db\Migration;

/**
 * Class m231105_172116_product_info
 */
class m231105_172116_product_info extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
         $this->createTable('product_info', [
             'id' => $this->primaryKey(),
             'category_id' => $this->integer()->notNull(),
             'product_id' => $this->integer()->notNull(),
             'property_fields' => $this->string()->Null(),//
             'short_description' => $this->string()->Null(),
             'created_at' => $this->timestamp()->defaultExpression('NOW()'),//дата добавления
             'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE NOW()'),//дата последнего обновления

         ]);

        $this->addForeignKey('fk-product_info-product_id-product-id', 'product_info', 'product_id', 'product', 'id', 'CASCADE', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231105_172116_product_info cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231105_172116_product_info cannot be reverted.\n";

        return false;
    }
    */
}
