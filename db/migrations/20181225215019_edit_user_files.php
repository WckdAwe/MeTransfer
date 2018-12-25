<?php


use Phinx\Migration\AbstractMigration;

class EditUserFiles extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('user_files');
        $table->changeColumn('belongs_to', 'integer', ['null' => true])
              ->save();
    }

    public function down(){
        $table = $this->table('user_files');
        $table->changeColumn('belongs_to', 'integer', ['null' => false])
            ->save();
    }
}
