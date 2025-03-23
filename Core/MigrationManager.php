<?php

class MigrationManager
{
    private $conn;
    private $migrationsTable = 'migrations';

    public function __construct()
    {
        $this->conn = (new Conexao())->getConn();
        $this->createMigrationsTable();
    }

    /**
     * Cria a tabela de controle de migrations se não existir
     */
    private function createMigrationsTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `{$this->migrationsTable}` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `migration` VARCHAR(255) NOT NULL,
            `batch` INT NOT NULL,
            `executed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

        $this->conn->exec($sql);
    }

    /**
     * Executa todas as migrations pendentes
     */
    public function migrate()
    {
        $migrations = $this->getPendingMigrations();
        if (empty($migrations)) {
            return "Nenhuma migration pendente.";
        }

        $batch = $this->getNextBatchNumber();
        $executed = [];

        foreach ($migrations as $migration) {
            $migrationClass = $this->getMigrationClassName($migration);
            if (!class_exists($migrationClass)) {
                require_once __DIR__ . "/../Migrations/{$migration}.php";
            }

            $instance = new $migrationClass();
            try {
                $instance->up();
                $this->markMigrationAsExecuted($migration, $batch);
                $executed[] = $migration;
            } catch (\Exception $e) {
                return "Erro ao executar migration {$migration}: " . $e->getMessage();
            }
        }

        return count($executed) . " migration(s) executada(s) com sucesso.";
    }

    /**
     * Reverte a última batch de migrations
     */
    public function rollback()
    {
        $lastBatch = $this->getLastBatchNumber();
        if (!$lastBatch) {
            return "Nenhuma migration para reverter.";
        }

        $migrations = $this->getMigrationsFromBatch($lastBatch);
        $reverted = [];

        foreach (array_reverse($migrations) as $migration) {
            $migrationClass = $this->getMigrationClassName($migration);
            if (!class_exists($migrationClass)) {
                require_once __DIR__ . "/../Migrations/{$migration}.php";
            }

            $instance = new $migrationClass();
            try {
                $instance->down();
                $this->removeMigrationRecord($migration);
                $reverted[] = $migration;
            } catch (\Exception $e) {
                return "Erro ao reverter migration {$migration}: " . $e->getMessage();
            }
        }

        return count($reverted) . " migration(s) revertida(s) com sucesso.";
    }

    /**
     * Obtém todas as migrations pendentes
     */
    private function getPendingMigrations()
    {
        $executedMigrations = $this->getExecutedMigrations();
        $migrationFiles = glob(__DIR__ . "/../Migrations/*.php");
        $pendingMigrations = [];

        foreach ($migrationFiles as $file) {
            $migration = basename($file, '.php');
            if (!in_array($migration, $executedMigrations)) {
                $pendingMigrations[] = $migration;
            }
        }

        // Ordena as migrations pelo prefixo numérico
        usort($pendingMigrations, function($a, $b) {
            // Extrai o prefixo numérico (assume formato: 001-NomeDaMigration)
            preg_match('/^(\d+)-/', $a, $matchesA);
            preg_match('/^(\d+)-/', $b, $matchesB);
            
            $prefixA = isset($matchesA[1]) ? (int)$matchesA[1] : PHP_INT_MAX;
            $prefixB = isset($matchesB[1]) ? (int)$matchesB[1] : PHP_INT_MAX;
            
            return $prefixA - $prefixB;
        });

        return $pendingMigrations;
    }

    /**
     * Obtém todas as migrations já executadas
     */
    private function getExecutedMigrations()
    {
        $stmt = $this->conn->query("SELECT migration FROM {$this->migrationsTable}");
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * Obtém o próximo número de batch
     */
    private function getNextBatchNumber()
    {
        $stmt = $this->conn->query("SELECT MAX(batch) FROM {$this->migrationsTable}");
        return ($stmt->fetchColumn() ?: 0) + 1;
    }

    /**
     * Obtém o último número de batch
     */
    private function getLastBatchNumber()
    {
        $stmt = $this->conn->query("SELECT MAX(batch) FROM {$this->migrationsTable}");
        return $stmt->fetchColumn();
    }

    /**
     * Obtém as migrations de uma batch específica
     */
    private function getMigrationsFromBatch($batch)
    {
        $stmt = $this->conn->prepare("SELECT migration FROM {$this->migrationsTable} WHERE batch = ?");
        $stmt->execute([$batch]);
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * Marca uma migration como executada
     */
    private function markMigrationAsExecuted($migration, $batch)
    {
        $stmt = $this->conn->prepare("INSERT INTO {$this->migrationsTable} (migration, batch) VALUES (?, ?)");
        $stmt->execute([$migration, $batch]);
    }

    /**
     * Remove o registro de uma migration
     */
    private function removeMigrationRecord($migration)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->migrationsTable} WHERE migration = ?");
        $stmt->execute([$migration]);
    }

    /**
     * Obtém o nome da classe de uma migration
     */
    private function getMigrationClassName($migration)
    {
        // Remove o prefixo numérico (ex: 001-) do nome da migration
        return preg_replace('/^\d+-/', '', $migration);
    }
}