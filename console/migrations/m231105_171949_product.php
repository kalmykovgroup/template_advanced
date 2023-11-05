<?php

use yii\db\Migration;

/**
 * Class m231105_171949_product
 */
class m231105_171949_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('product', [
            'id' => $this->primaryKey(),
            'category_id' => $this->string()->Null(),
            'name' => $this->string()->Null(),//Название товара
            'status' => $this->integer()->Null(),//Статус товара
            'unit' => $this->string()->Null(), //Единица измерения
            'count' => $this->integer()->Null(), // кол-во в наличии
            'start_price' =>$this->string()->Null(),//Цена закупки
            'price' =>$this->string()->Null(), //Текущая цена
            'old_price' =>$this->string()->Null(), //Старая цена

        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231105_171949_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231105_171949_product cannot be reverted.\n";

        return false;
    }
    */
}
