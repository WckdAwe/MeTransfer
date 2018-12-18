<?php


use Phinx\Migration\AbstractMigration;

class AddUserLogins extends AbstractMigration
{
    public function up(){
        $rows = [
            [
                'user_id'    => 1,
                'created'  => time(),
            ],
            [
                'user_id'    => 2,
                'created'  => time(),
            ]
        ];

        $this->table('user_logins')->insert($rows)->save();
    }

    public function down(){
        $this->execute('DELETE FROM user_logins');
    }
}
