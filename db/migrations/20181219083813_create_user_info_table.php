<?php


use Phinx\Migration\AbstractMigration;

class CreateUserInfoTable extends AbstractMigration
{
     public function up(){
		$table = $this->table('users');
		$table->addColumn('username', 'string', ['limit' => 30])
			  ->addColumn('email', 'string', ['limit' => 255])
			  ->addColumn('password', 'string', ['limit' => 40])
			  ->save();	 

			  
		$table = $this->table('user_info', ['id' => false, 'primary_key' => 'user_id']);
		$table->addColumn('user_id', 'integer')
			  ->addColumn('first_name', 'string', ['limit' => 40])
			  ->addColumn('last_name', 'string', ['limit' => 50])
			  ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
			  ->save();
					 
	}
	
	public function down(){
		$this->table('user_info')->drop()->save();
		$this->table('users')->drop()->save();
	}
}
