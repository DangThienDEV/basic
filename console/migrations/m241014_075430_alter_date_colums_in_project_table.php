<?php

use yii\db\Migration;

/**
 * Class m241014_075430_alter_date_colums_in_project_table
 */
class m241014_075430_alter_date_colums_in_project_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn("project", "start_date", $this->date());
        $this->alterColumn("project", "end_date", $this->date());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn("project","start_date", $this->integer());
        $this->alterColumn("project","end_date", $this->integer());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241014_075430_alter_date_colums_in_project_table cannot be reverted.\n";

        return false;
    }
    */
}
