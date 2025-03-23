<?php

class BackupSql extends Conexao
{
    private $conn;
    private $backupDir;

    public function __construct($backupDir = 'backups/')
    {
        $this->conn = parent::getConn();
        $this->backupDir = rtrim($backupDir, '/') . '/';
        if (!is_dir($this->backupDir)) {
            mkdir($this->backupDir, 0777, true);
        }
    }

    public function gerarBackup($fileName = null)
    {
        $fileName = $fileName ?: 'backup_' . getenv('DB_NAME') . '--' . date('d-m-Y_His') . '.sql';
        $filePath = $this->backupDir . $fileName;

        $sql = "-- Backup do banco de dados: " . getenv('DB_NAME') . "\n-- Data: " . date('Y-m-d H:i:s') . "\n\n";

        $tables = $this->getTables();
        foreach ($tables as $table) {
            $sql .= $this->getTableStructure($table);
            $sql .= $this->getTableData($table);
        }

        file_put_contents($filePath, $sql);
        return $filePath;
    }

    private function getTables()
    {
        $stmt = $this->conn->query("SHOW TABLES");
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    private function getTableStructure($table)
    {
        $stmt = $this->conn->query("SHOW CREATE TABLE `{$table}`");
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return "DROP TABLE IF EXISTS `{$table}`;\n" . $row['Create Table'] . ";\n\n";
    }

    private function getTableData($table)
    {
        $stmt = $this->conn->query("SELECT * FROM `{$table}`");
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if (empty($rows)) {
            return "";
        }

        $sql = "INSERT INTO `{$table}` VALUES\n";
        $values = [];

        foreach ($rows as $row) {
            $escapedValues = array_map(function ($value) {
                if (is_null($value)) {
                    return "NULL";
                } elseif (is_numeric($value)) {
                    return $value;
                } else {
                    return "'" . addslashes($value) . "'";
                }
            }, array_values($row));
            $values[] = "(" . implode(", ", $escapedValues) . ")";
        }

        $sql .= implode(",\n", $values) . ";\n\n";
        return $sql;
    }
}
