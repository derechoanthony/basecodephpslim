<?php


use Phinx\Seed\AbstractSeed;

class Position extends AbstractSeed
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
                'title' => 'Admin Manager',
            ],
            [
                'id' => 2,
                'title' => 'User',
            ]
        ];

        $this->execute('SET FOREIGN_KEY_CHECKS=0;');
        $position = $this->table('position');
        $position->truncate();
        $this->execute('SET FOREIGN_KEY_CHECKS=1;');

        $position->insert($data)
              ->save();
    }
}
