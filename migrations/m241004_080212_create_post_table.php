<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%post}}`.
 */
class m241004_080212_create_post_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%post}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'content' => $this->text(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer(),
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
