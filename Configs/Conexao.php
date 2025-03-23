<?php

require_once ("./functions.php");

class Conexao extends Controller
{
    private static $host = null;
    private static $user = null;
    private static $pass = null;
    private static $db_name = null;

    private static $connection = null;

    /**
     * Carrega as configurações do banco de dados.
     */
    private static function loadConfig()
    {
        self::$host = getenv('DB_HOST');
        self::$user = getenv('DB_USER');
        self::$pass = getenv('DB_PASS');
        self::$db_name = getenv('DB_NAME');
    }

    /**
     * Conecta ao banco de dados com o padrão Singleton.
     * Retorna um objeto PDO.
     */
    private static function conectar()
    {
        try {
            if (self::$connection == null) {
                self::loadConfig();
                $dsn = 'mysql:host=' . self::$host . ';dbname=' . self::$db_name;
                $options = [
                    \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8',
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_PERSISTENT => true
                ];
                self::$connection = new \PDO($dsn, self::$user, self::$pass, $options);
            }
        } catch (\PDOException $e) {
            logError("Erro PDO: {$e->getMessage()}");
            Controller::setErrorAndRedirect(
                "Erro ao conectar ao banco de dados. Por favor, tente novamente.",
                "/",
                "Erro de Conexão",
                "error"
            );
        }
        return self::$connection;
    }

    /**
     * Retorna a instância única da conexão.
     */
    public static function getConn()
    {
        return self::conectar();
    }
}