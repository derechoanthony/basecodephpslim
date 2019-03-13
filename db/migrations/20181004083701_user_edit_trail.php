<?php


use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class UserEditTrail extends AbstractMigration
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
        $table = $this->table("user_edit_trail",
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

        $table->addColumn('user_id', 'integer',
                            ['null' => false,
                             'limit' => MysqlAdapter::INT_REGULAR,
                             'precision' => 10,
                             'signed' => false,
                             'after' => 'id']);

        $table->addColumn('action', 'char',
                            ['null' => true,
                             'limit' => 10,
                             'collation' => "utf8_general_ci",
                             'encoding' => "utf8",
                             'after' => 'user_id']);

        $table->addColumn('remarks', 'string',
                            ['null' => true,
                             'limit' => 50,
                             'collation' => "utf8_general_ci",
                             'encoding' => "utf8",
                             'after' => 'action']);

        $table->addColumn('created_by', 'string',
                            ['null' => true,
                             'limit' => 50,
                             'collation' => "utf8_general_ci",
                             'encoding' => "utf8",
                             'after' => 'remarks']);

         $table->addColumn('created_at', 'integer',
                             ['null' => false,
                              'limit' => MysqlAdapter::INT_REGULAR,
                              'precision' => 10,
                              'signed' => false,
                              'after' => 'created_by']);

        $table->addColumn('updated_at', 'timestamp',
                            ['null' => true,
                             'after' => 'updated_at']);

        $table->addColumn('updated_by', 'integer',
                            ['null' => false,
                             'limit' => MysqlAdapter::INT_REGULAR,
                             'precision' => 10,
                             'signed' => false,
                             'after' => 'updated_at']);

        $table->addIndex(['user_id'],
                        ['name' => "user_edit_trail_user",
                         'unique' => false]);

        $table->addIndex(['updated_by'],
                        ['name' => "user_edit_trail_user_editor",
                         'unique' => false]);

        $table->create();
    }
}
