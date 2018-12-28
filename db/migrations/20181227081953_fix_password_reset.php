<?php


use Phinx\Migration\AbstractMigration;

class FixPasswordReset extends AbstractMigration
{
    public function change(){
		$table = $this->table('password_reset');
		$table->addColumn('belongsTo', 'integer', ['after' => 'used'])
			  ->addForeignKey('belongsTo', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
			  ->update();
    }
}
