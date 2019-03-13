<?php


use Phinx\Seed\AbstractSeed;

class TruncateAll extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS=0;');

        /**
        * Truncate table
        */
        $this->table('password_log')->truncate();
        $this->table('user')->truncate();
        $this->table('user_edit_trail')->truncate();
        $this->table('user_roles')->truncate();
        $this->table('user_change_password')->truncate();
        $this->table('user_activation_validity')->truncate();


        $this->execute('SET FOREIGN_KEY_CHECKS=1;');

    }
}
