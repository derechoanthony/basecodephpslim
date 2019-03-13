<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class UserActivationValidity extends AbstractMigration
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
        $table = $this->table(
            "user_activation_validity",
            [
                'id' => false,
                'primary_key' => ["id"],
                'engine' => "InnoDB",
                'encoding' => "utf8",
                'collation' => "utf8_general_ci",
                'comment' => "",
                'row_format' => "Dynamic"
            ]
        );

        $table->addColumn(
            'id',
            'integer',
            [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'precision' => 10,
                'signed' => false,
                'identity' => 'enable'
            ]
        );
        $table->addColumn(
            'user_id',
            'integer',
            [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'precision' => 10,
                'signed' => false,
            ]
        );

        $table->addColumn(
            'valid_at',
            'timestamp',
            [
                'null' => true,
                'after' => 'user_id',
            ]
        );
        $table->addColumn(
            'created_at',
            'timestamp',
            [
                'null' => true,
                'after' => 'expiration',
            ]
        );
        $table->addColumn(
            'updated_at',
            'timestamp',
            [
                'null' => true,
                'after' => 'created_at',
            ]
        );
        $table->addColumn(
            'deleted_at',
            'timestamp',
            [
                'null' => true,
                'after' => 'created_at',
            ]
        );

        $table->addIndex(
            ['id'],
            [
                'name' => "user_change_password",
                'unique' => false,
            ]
        );

        $table->create();
    }
}
