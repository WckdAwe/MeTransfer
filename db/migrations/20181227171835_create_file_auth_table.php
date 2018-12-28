<?php


use Phinx\Migration\AbstractMigration;

class CreateFileAuthTable extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('file_auth');
        if($table->exists()) $table->drop()->save();
        $table->addColumn('file_id', 'integer')
            ->addColumn('email', 'string', ['limit' => 256])
            ->addIndex(['file_id'])
            ->addForeignKey('file_id', 'user_files', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
            ->save();
    }

    public function down()
    {
        $this->table('file_auth')
              ->dropForeignKey('file_id')
              ->drop()->save();
    }
}
