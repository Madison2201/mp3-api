<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%post}}`.
 */
class m250109_064202_create_post_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%post}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'description' => $this->text(),
            'file' => $this->binary()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer(),
            'status' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%post}}');
    }
}
