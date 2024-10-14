<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%project}}`.
 */
class m241014_022534_create_project_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%project}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'tech_stack' => $this->text()->notNull(),
            'descritption' => $this->text()->notNull(),
            'start_date' => $this->integer()->notNull(),
            'end_date' => $this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%project}}');
    }
}
