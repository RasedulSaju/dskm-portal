<?php
// core/DB.php

namespace Core;

use PDO;
use PDOException;

class DB
{
    private static ?DB $instance = null;
    private PDO $pdo;
    private array $config;

    private function __construct(array $config)
    {
        $this->config = $config;
        $this->connect();
    }

    private function connect(): void
    {
        $cfg = $this->config['connections'][$this->config['default']];
        $dsn = "mysql:host={$cfg['host']};port={$cfg['port']};dbname={$cfg['database']};charset={$cfg['charset']}";

        // Build PDO options - compatible with PHP 7.x, 8.x, and 8.5+
        $pdoOptions = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        // Use correct constant depending on PHP version
        if (PHP_VERSION_ID >= 80500 && defined('Pdo\\Mysql::ATTR_INIT_COMMAND')) {
            $pdoOptions[\Pdo\Mysql::ATTR_INIT_COMMAND] = "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci";
        } elseif (defined('PDO::MYSQL_ATTR_INIT_COMMAND')) {
            $pdoOptions[PDO::MYSQL_ATTR_INIT_COMMAND] = "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci";
        }

        try {
            $this->pdo = new PDO($dsn, $cfg['username'], $cfg['password'], $pdoOptions);
            // Also run charset command directly for safety
            $this->pdo->exec("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
        } catch (PDOException $e) {
            error_log('DB Connection Error: ' . $e->getMessage());
            throw new \RuntimeException('Database connection failed. Please try again later.');
        }
    }

    public static function getInstance(): DB
    {
        if (self::$instance === null) {
            $config = require dirname(__DIR__) . '/config/database.php';
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    /**
     * Execute a query and return all results
     */
    public function query(string $sql, array $params = []): array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Execute a query and return single row
     */
    public function queryOne(string $sql, array $params = []): ?array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Execute INSERT/UPDATE/DELETE
     */
    public function execute(string $sql, array $params = []): int
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    /**
     * Insert and return last insert ID
     */
    public function insert(string $table, array $data): int
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO `{$table}` ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array_values($data));
        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Update rows matching condition
     */
    public function update(string $table, array $data, array $where): int
    {
        $set = implode(', ', array_map(fn($k) => "`{$k}` = ?", array_keys($data)));
        $cond = implode(' AND ', array_map(fn($k) => "`{$k}` = ?", array_keys($where)));
        $sql = "UPDATE `{$table}` SET {$set} WHERE {$cond}";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([...array_values($data), ...array_values($where)]);
        return $stmt->rowCount();
    }

    /**
     * Delete rows
     */
    public function delete(string $table, array $where): int
    {
        $cond = implode(' AND ', array_map(fn($k) => "`{$k}` = ?", array_keys($where)));
        $sql = "DELETE FROM `{$table}` WHERE {$cond}";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array_values($where));
        return $stmt->rowCount();
    }

    /**
     * Count rows
     */
    public function count(string $table, string $where = '1', array $params = []): int
    {
        $row = $this->queryOne("SELECT COUNT(*) as cnt FROM `{$table}` WHERE {$where}", $params);
        return (int) ($row['cnt'] ?? 0);
    }

    public function beginTransaction(): void { $this->pdo->beginTransaction(); }
    public function commit(): void          { $this->pdo->commit(); }
    public function rollback(): void        { $this->pdo->rollBack(); }
    public function lastInsertId(): int     { return (int) $this->pdo->lastInsertId(); }
}
