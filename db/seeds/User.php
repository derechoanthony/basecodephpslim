<?php


use Phinx\Seed\AbstractSeed;


class User extends AbstractSeed
{
    public function getDependencies()
    {
        return [
            'TruncateAll'
        ];
    }

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
        $data = [
            [
                'id' => 1,
                'first_name' => 'Administrator',
                'middle_name' => '',
                'last_name' => '',
                'password' => '$2y$10$6QlqfoXUAAluMfrHjwgA6u9tvvTcdH2NUCGMYrIuLqbTLuF.2VkQG',
                'email' => 'admin@test.com',
                'position_id' => 1,
                'deleted_at' => null,
                'created_at' => date("Y-m-d H:i:s"),
                'is_verified' => 1
            ]

        ];

        $this->execute('SET FOREIGN_KEY_CHECKS=0;');
        $users = $this->table('user');
        $users->truncate();
        $this->execute('SET FOREIGN_KEY_CHECKS=1;');

        $users->insert($data)
              ->save();
    }
}
