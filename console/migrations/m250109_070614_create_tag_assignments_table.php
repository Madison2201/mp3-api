<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tag_assignments}}`.
 */
class m250109_070614_create_tag_assignments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tag_assignments}}', [
            'id_post' => $this->integer()->notNull(),
            'id_tag' => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey('{{%pk-tag_assignments}}', '{{%tag_assignments}}', ['id_post', 'id_tag']);

        $this->createIndex('{{%idx-tag_assignments-id_post}}', '{{%tag_assignments}}', 'id_post');
        $this->createIndex('{{%idx-tag_assignments-id_tag}}', '{{%tag_assignments}}', 'id_tag');

        $this->addForeignKey('{{%fk-tag_assignments-id_post}}', '{{%tag_assignments}}', 'id_post', '{{%post}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-tag_assignments-id_tag}}', '{{%tag_assignments}}', 'id_tag', '{{%tags}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tag_assignments}}');
    }
}
