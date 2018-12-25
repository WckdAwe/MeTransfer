<?php


use Phinx\Migration\AbstractMigration;
use Phinx\Util\Literal;

class CreateUserFiles extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('user_files');
        if($table->exists()) $table->drop()->save();
        $table->addColumn('uid', 'string', ['limit' => 36])
              ->addColumn('file_name', 'string', ['limit' => 256])
              ->addColumn('file_ext', 'string', ['limit' => 32])
              ->addColumn('file_size', 'integer')
              ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('delete_at', 'timestamp', ['null' => false])
              ->addColumn('belongs_to', 'integer')
              ->addIndex(['uid'], ['unique' => true])
              ->addForeignKey('belongs_to', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
              ->save();
    }

    public function down(){
        $this->table('user_files')->drop()->save();
    }
}
