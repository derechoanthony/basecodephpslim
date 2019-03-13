<?php


use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class UserRoles extends AbstractMigration
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
        $table = $this->table("user_roles", 
                            ['id' => false, 
                            'primary_key' => ["user_id", "role_id"], 
                            'engine' => "InnoDB", 
                            'encoding' => "utf8", 
                            'collation' => "utf8_general_ci", 
                            'comment' => "", 
                            'row_format' => "Dynamic"]);

        $table->addColumn('user_id', 'integer', 
                            ['null' => false, 
                            'limit' => MysqlAdapter::INT_REGULAR, 
                            'precision' => 10, 
                            'signed' => false]);

        $table->addColumn('role_id', 'integer', 
                            ['null' => false, 
                            'limit' => MysqlAdapter::INT_REGULAR, 
                            'precision' => 10, 
                            'signed' => false, 
                            'after' => 'user_id']);

        $table->addIndex(['role_id'], 
                        ['name' => "user_roles_role_idx", 
                         'unique' => false]);

        $table->create();
    }
}
