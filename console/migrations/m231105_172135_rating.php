<?php

use yii\db\Migration;

/**
 * Class m231105_172135_rating
 */
class m231105_172135_rating extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable('rating', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'value' => $this->integer()->notNull(), //Кол-во поставленных звездочек
            'review_id' => $this->integer()->Null(), //Ссылка на отзыв, если он есть
            'created_at' => $this->timestamp()->defaultExpression('NOW()'),
        ]);
        $this->addForeignKey('fk-rating-review_id-reviews-id', 'rating', 'review_id', 'reviews', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-rating-product_id-product-id', 'rating', 'product_id', 'product', 'id', 'CASCADE', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231105_172135_rating cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231105_172135_rating cannot be reverted.\n";

        return false;
    }
    */
}
