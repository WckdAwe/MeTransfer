<?php


use Phinx\Migration\AbstractMigration;

class AddAdminColumnToUsers extends AbstractMigration
{
    public function up()
    {
        $this->table('users')
             ->addColumn('is_admin', 'boolean', ['default'=>false, 'after'=>'password'])
             ->save();
    }

    public function down()
    {
        $this->table('users')
            ->removeColumn('is_admin')
            ->save();
    }
}
