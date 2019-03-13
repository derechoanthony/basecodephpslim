<?php


use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class PasswordLog extends AbstractMigration
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
        $table = $this->table("password_log", 
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

        $table->addColumn('password', 'string', 
                            ['null' => true, 
                             'limit' => 150, 
                             'collation' => "utf8_general_ci", 
                             'encoding' => "utf8", 
                             'after' => 'user_id']);

        $table->addColumn('created_at', 'timestamp', 
                            ['null' => true, 
                             'after' => 'password']);
                             
        $table->addIndex(['user_id','password'], 
                        ['name' => "password_unique", 
                         'unique' => true]);

        $table->addIndex(['user_id'], 
                        ['name' => "password_log_user", 
                         'unique' => false]);

        $table->create();

    }
}
