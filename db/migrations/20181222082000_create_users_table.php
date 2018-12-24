<?php


use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateUsersTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $table = $this->table('users');
        if($table->exists()) $table->drop();
        $table->addColumn('username', 'string', ['limit' => 30])
              ->addColumn('email', 'string', ['limit' => 255])
              ->addColumn('password', 'string', ['limit' => 60])
              ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
              ->save();

        $table = $this->table('user_info', ['id' => 'user_id']);
        if($table->exists()) $table->drop();
        $table->addColumn('first_name', 'string', ['limit' => 30])
              ->addColumn('last_name', 'string', ['limit' => 255])
              ->addColumn('gender', 'integer', ['limit' => MysqlAdapter::INT_TINY])
              ->addColumn('birthday', 'datetime')
              ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
              ->save();
    }

    public function down()
    {
        $table = $this->table('user_info');
        $table->dropForeignKey('user_id');
        $table->drop()->save();
        $this->table('users')->drop()->save();
    }
}
