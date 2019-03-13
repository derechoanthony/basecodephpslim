<?php


use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class Role extends AbstractMigration
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
        $table = $this->table("role", 
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

        $table->addColumn('title', 'string', 
                            ['null' => true, 
                             'limit' => 40, 
                             'collation' => "utf8_general_ci", 
                             'encoding' => "utf8", 
                             'after' => 'id']);

        $table->addColumn('created_at', 'timestamp', 
                            ['null' => true, 
                             'after' => 'title']);

        $table->addColumn('created_by', 'integer', 
                            ['null' => false, 
                             'limit' => MysqlAdapter::INT_REGULAR, 
                             'precision' => 10, 
                             'signed' => false, 
                             'after' => 'created_at']);

        $table->addColumn('updated_at', 'timestamp', 
                            ['null' => true, 
                             'after' => 'created_by']);

        $table->addColumn('updated_by', 'integer', 
                            ['null' => false, 
                             'limit' => MysqlAdapter::INT_REGULAR, 
                             'precision' => 10, 
                             'signed' => false, 
                             'after' => 'updated_at']);

        $table->addColumn('status', 'integer', 
                            ['null' => true, 
                             'limit' => MysqlAdapter::INT_TINY, 
                             'precision' => 3, 
                             'after' => 'updated_by']);

        $table->addIndex(['title'], 
                        ['name' => "title_UNIQUE", 
                         'unique' => true]);

        $table->addIndex(['created_by'], 
                        ['name' => "role_creator_idx", 
                         'unique' => false]);

        $table->addIndex(['updated_by'], 
                        ['name' => "role_updater_idx", 
                         'unique' => false]);
                         
        $table->create();

    }
}
