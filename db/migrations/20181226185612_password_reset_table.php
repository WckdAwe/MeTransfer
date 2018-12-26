<?php


use Phinx\Migration\AbstractMigration;

class PasswordResetTable extends AbstractMigration
{

    public function up(){
		$password_reset_table = $this->table('password_reset');
		if($password_reset_table->exists()) $password_reset_table->drop()->save();
		$password_reset_table->addColumn('uid', 'string', ['limit' => 36])
							 ->addColumn('used', 'integer', ['limit' => 1])
							 ->addForeignKey('belongTo', 'users', 'id',['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
							 ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
							 ->save();
			  
    }
	public function down(){
		$table = $this->table('password_reset');
        $table->dropForeignKey('belongTo');
        $table->drop()->save();
    }
}
