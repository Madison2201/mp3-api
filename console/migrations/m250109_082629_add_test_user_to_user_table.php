<?php

use yii\db\Migration;

/**
 * Class m250109_082629_add_test_user_to_user_table
 */
class m250109_082629_add_test_user_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('user', [
            'username' => 'platon',
            'email' => 'testuser@example.com',
            'password_hash' => Yii::$app->security->generatePasswordHash('password123'), // Убедитесь, что это безопасный хэш пароля
            'auth_key' => Yii::$app->security->generateRandomString(),
            'status' => 10,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('user', [
            'username' => 'saturn',
            'email' => 'testuser2@example.com',
            'password_hash' => Yii::$app->security->generatePasswordHash('password123'), // Убедитесь, что это безопасный хэш пароля
            'auth_key' => Yii::$app->security->generateRandomString(),
            'status' => 10,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {


    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250109_082629_add_test_user_to_user_table cannot be reverted.\n";

        return false;
    }
    */
}
