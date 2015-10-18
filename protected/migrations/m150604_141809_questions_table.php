<?php

class m150604_141809_questions_table extends CDbMigration
{
	public function up()
	{
        $this->createTable('questions', array(
            'id' => 'pk',
            'user_id' => 'int NOT NULL',
            'question' => 'text',
            'answer' => 'text',
            'status' => 'int', /*0 - without answer, 1 - with answer,2-deleted*/
            'time_create' => 'int',
        ));
	}

	public function down()
	{
        $this->dropTable('questions');
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}