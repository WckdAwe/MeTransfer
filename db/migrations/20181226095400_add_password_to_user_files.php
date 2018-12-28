<?php


use Phinx\Migration\AbstractMigration;
use \Phinx\Db\Adapter\MysqlAdapter;

class AddPasswordToUserFiles extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('user_files');
        $table->addColumn('share_type', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'default'=>0])
              ->addColumn('password', 'string', ['limit' => 60, 'null' => true])
              ->save();
    }

    public function down(){
        $table = $this->table('user_files');
        $table->removeColumn('share_type')
              ->removeColumn('password')->save();
    }
}
