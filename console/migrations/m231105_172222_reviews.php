<?php

use yii\db\Migration;

/**
 * Class m231105_172222_reviews
 */
class m231105_172222_reviews extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
         $this->createTable('reviews', [ //Отзыв
             'id' => $this->primaryKey(),
             'message' => $this->string(255)->Null(),
             'is_photo' =>$this->boolean(), //Наличие фото к отзыву
         ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231105_172222_reviews cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231105_172222_reviews cannot be reverted.\n";

        return false;
    }
    */
}
