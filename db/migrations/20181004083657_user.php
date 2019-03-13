<?php


use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class User extends AbstractMigration
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
    public function change()
    {
        $table = $this->table("user",
                            ['id' => false,
                             'primary_key' => ["id"],
                             'engine' => "InnoDB",
                             'encoding' => "utf8",
                             'collation' => "utf8_general_ci",
                             'comment' => "",
                             'row_format' => "Dynamic"]);

        $table->addColumn('id', 'integer',
                            ['null' => false,
                             'limit' => MysqlAdapter::INT_REGULAR,
                             'precision' => 10,
                             'signed' => false,
                             'identity' => 'enable']);

        $table->addColumn('first_name', 'string',
                            ['null' => true,
                             'limit' => 50,
                             'collation' => "utf8_general_ci",
                             'encoding' => "utf8",
                             'after' => 'id']);

        $table->addColumn('middle_name',
         'string',
                            ['null' => true,
                             'limit' => 50,
                             'collation' => "utf8_general_ci",
                             'encoding' => "utf8",
                             'after' => 'first_name']);

        $table->addColumn('last_name', 'string',
                            ['null' => true,
                             'limit' => 50,
                             'collation' => "utf8_general_ci",
                             'encoding' => "utf8",
                             'after' => 'middle_name']);

        $table->addColumn('password', 'string',
                            ['null' => true,
                             'limit' => 150,
                             'collation' => "utf8_general_ci",
                             'encoding' => "utf8",
                             'after' => 'last_name']);

        $table->addColumn('email', 'string',
                            ['null' => true,
                             'limit' => 50,
                             'collation' => "utf8_general_ci",
                             'encoding' => "utf8",
                             'after' => 'password']);

        $table->addColumn('position_id', 'integer',
                            ['null' => true,
                            'limit' => MysqlAdapter::INT_REGULAR,
                            'precision' => 10,
                            'signed' => false,
                            'after' => 'email']);

        $table->addColumn('status', 'integer',
                            ['null' => true,
                             'limit' => MysqlAdapter::INT_TINY,
                             'precision' => 3,
                             'after' => 'position_id']);

        $table->addColumn('created_at', 'timestamp',
                             ['null' => true,
                             'default' => 'CURRENT_TIMESTAMP',
                              'after' => 'email']);
          $table->addColumn('deleted_at', 'timestamp', 
                               ['null' => true,
                               'default' => 'CURRENT_TIMESTAMP',
                                'after' => 'email']);

        $table->addIndex(['email'],
                        ['name' => "email_UNIQUE",
                         'unique' => true]);

        $table->addIndex(['position_id'],
                         ['name' => "position_id_idx",
                          'unique' => false]);
        $table->create();

    }
}
