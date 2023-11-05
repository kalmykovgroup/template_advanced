<?php

use yii\db\Migration;

/**
 * Class m231105_192122_replies_to_reviews
 */
class m231105_192122_replies_to_reviews extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable('replies_to_reviews', [ //Ответы админов на отзывы
            'id' => $this->primaryKey(),
            'review_id' => $this->integer()->notNull(),
            'message' => $this->string(255)->Null(),
            'created_at' => $this->timestamp()->defaultExpression('NOW()'),
        ]);

        $this->addForeignKey('fk-replies_to_reviews-review_id-review-id', 'replies_to_reviews', 'review_id', 'review', 'id', 'CASCADE', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231105_192122_replies_to_reviews cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231105_192122_replies_to_reviews cannot be reverted.\n";

        return false;
    }
    */
}
