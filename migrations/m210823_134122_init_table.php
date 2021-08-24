<?php

use yii\db\Migration;

/**
 * Class m210823_134122_init_table
 */
class m210823_134122_init_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('comments', [
            'id' => $this->primaryKey(),
            'content' => $this->text(),
            'author' => $this->string()->notNull(),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('comments');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210823_134122_init_table cannot be reverted.\n";

        return false;
    }
    */
}
