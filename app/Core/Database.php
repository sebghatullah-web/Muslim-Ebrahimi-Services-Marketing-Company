<?php

namespace App\Core;

use PDO;
use PDOException;

/**
 * اتصال پایگاه داده با استفاده از PDO (الگوی Singleton).
 * همه کوئری‌ها با prepared statements اجرا می‌شوند تا در برابر SQL Injection محافظت شوند.
 */
class Database
{
    private static ?PDO $pdo = null;

    public static function connect(): PDO
    {
        if (self::$pdo instanceof PDO) {
            return self::$pdo;
        }

        $db = Config::get('db');
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $db['host'],
            $db['port'],
            $db['database'],
            $db['charset']
        );

        try {
            self::$pdo = new PDO($dsn, $db['username'], $db['password'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            if (Config::get('app.debug')) {
                http_response_code(500);
                exit('خطای اتصال به پایگاه داده: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
            }
            http_response_code(500);
            exit('خطای اتصال به پایگاه داده. لطفاً تنظیمات .env و در دسترس بودن MySQL را بررسی کنید.');
        }

        return self::$pdo;
    }

    /**
     * اجرای کوئری با پارامترها و بازگرداندن statement.
     */
    public static function run(string $sql, array $params = []): \PDOStatement
    {
        $stmt = self::connect()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public static function fetch(string $sql, array $params = []): mixed
    {
        return self::run($sql, $params)->fetch();
    }

    public static function fetchAll(string $sql, array $params = []): array
    {
        return self::run($sql, $params)->fetchAll();
    }

    public static function insert(string $table, array $data): int
    {
        $columns = array_keys($data);
        $placeholders = array_map(fn($c) => ':' . $c, $columns);
        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $table,
            implode(', ', array_map(fn($c) => "`$c`", $columns)),
            implode(', ', $placeholders)
        );
        self::run($sql, $data);
        return (int) self::connect()->lastInsertId();
    }

    public static function update(string $table, array $data, string $where, array $whereParams = []): int
    {
        $setParts = [];
        foreach (array_keys($data) as $col) {
            $setParts[] = "`$col` = :$col";
        }
        $sql = sprintf('UPDATE %s SET %s WHERE %s', $table, implode(', ', $setParts), $where);
        $params = array_merge($data, $whereParams);
        $stmt = self::run($sql, $params);
        return $stmt->rowCount();
    }

    public static function delete(string $table, string $where, array $whereParams = []): int
    {
        $sql = sprintf('DELETE FROM %s WHERE %s', $table, $where);
        $stmt = self::run($sql, $whereParams);
        return $stmt->rowCount();
    }

    public static function count(string $table, string $where = '1', array $params = []): int
    {
        $sql = sprintf('SELECT COUNT(*) AS c FROM %s WHERE %s', $table, $where);
        $row = self::fetch($sql, $params);
        return (int) ($row['c'] ?? 0);
    }
}
