<?php

abstract class Migration
{
    protected $conn;

    public function __construct()
    {
        $this->conn = (new Conexao())->getConn();
    }

    /**
     * Execute as alterações no banco de dados
     */
    abstract public function up();

    /**
     * Reverta as alterações no banco de dados
     */
    abstract public function down();

    /**
     * Executa uma query SQL
     */
    protected function execute($sql)
    {
        try {
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute();
        } catch (\PDOException $e) {
            throw new \Exception("Erro ao executar migration: " . $e->getMessage());
        }
    }

    /**
     * Cria uma nova tabela
     */
    protected function createTable($tableName, $columns)
    {
        $sql = "CREATE TABLE IF NOT EXISTS `{$tableName}` (\n";
        $sql .= implode(",\n", $columns);
        $sql .= "\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        
        return $this->execute($sql);
    }

    /**
     * Remove uma tabela
     */
    protected function dropTable($tableName)
    {
        return $this->execute("DROP TABLE IF EXISTS `{$tableName}`;");
    }

    /**
     * Adiciona uma nova coluna
     */
    protected function addColumn($tableName, $columnName, $columnDefinition)
    {
        return $this->execute("ALTER TABLE `{$tableName}` ADD COLUMN `{$columnName}` {$columnDefinition};");
    }

    /**
     * Remove uma coluna
     */
    protected function dropColumn($tableName, $columnName)
    {
        return $this->execute("ALTER TABLE `{$tableName}` DROP COLUMN `{$columnName}`;");
    }

    /**
     * Modifica uma coluna existente
     */
    protected function modifyColumn($tableName, $columnName, $newDefinition)
    {
        return $this->execute("ALTER TABLE `{$tableName}` MODIFY COLUMN `{$columnName}` {$newDefinition};");
    }

    /**
     * Adiciona um índice
     */
    protected function addIndex($tableName, $indexName, $columns)
    {
        $columns = is_array($columns) ? implode('`, `', $columns) : $columns;
        return $this->execute("CREATE INDEX `{$indexName}` ON `{$tableName}` (`{$columns}`);");
    }

    /**
     * Remove um índice
     */
    protected function dropIndex($tableName, $indexName)
    {
        return $this->execute("DROP INDEX `{$indexName}` ON `{$tableName}`;");
    }
}