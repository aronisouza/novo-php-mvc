<?php

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->createTable('users', [
            '`id` INT AUTO_INCREMENT PRIMARY KEY',
            '`name` VARCHAR(255) NOT NULL',
            '`email` VARCHAR(255) NOT NULL UNIQUE',
            '`password` VARCHAR(255) NOT NULL',
            '`status` ENUM("active", "inactive") NOT NULL DEFAULT "active"',
            '`role` ENUM("admin", "user") NOT NULL DEFAULT "user"',
            '`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
            '`updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        ]);

        // Adicionando índices para melhor performance
        $this->addIndex('users', 'email_index', 'email');
        $this->addIndex('users', 'status_index', 'status');
    }

    public function down()
    {
        $this->dropTable('users');
    }
}
