<?php

declare(strict_types=1);

namespace App;

use PDO;
use PDOStatement;

class Database
{
    /**
     * @var PDO
     */
    protected $connection;

    /**
     * @var array
     */
    protected $options;

    public function __construct(array $options)
    {
        $this->options = $options;
    }

    public function find(string $query, array $params = []): array
    {
        $stmt = $this->executeStatement($query, $params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row === false) {
            $row = [];
        }

        return $row;
    }

    public function findAll(string $query, array $params = []): array
    {
        $stmt = $this->executeStatement($query, $params);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($results === false) {
            return [];
        }

        return $results;
    }

    public function fetchValue(string $query, array $params = [])
    {
        $stmt = $this->executeStatement($query, $params);
        $values = $stmt->fetch(PDO::FETCH_NUM);

        if ($values === false) {
            $values = [];
        }

        $value = null;
        if (isset($values[0])) {
            $value = $values[0];
        }

        return $value;
    }

    public function fetchKeyPair(string $query, array $params = []): array
    {
        $stmt = $this->executeStatement($query, $params);
        $results = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

        if ($results === false) {
            return [];
        }

        return $results;
    }

    public function fetchColumn(string $query, array $params = [], int $columnIndex = 0): array
    {
        $stmt = $this->executeStatement($query, $params);
        $results = $stmt->fetchAll(PDO::FETCH_COLUMN, $columnIndex);

        if ($results === false) {
            return [];
        }

        return $results;
    }

    public function count(string $table, bool $includeDeleted = true): int
    {
		if(!$includeDeleted) {
			$softDeleteable = (bool) $this->fetchValue("select exists (select 1 from information_schema.columns where table_name='".$table."' and column_name='deleted_at')");
			if($softDeleteable)
				return (int) $this->fetchValue('select count(*) from public.'.$table.' where deleted_at is null');
		}
			
		return (int) $this->fetchValue('select count(*) from public.'.$table);
    }

    public function execute(string $query, array $params = []): int
    {
        if (empty($params)) {
            return $this->getConnection()->exec($query);
        }

        $stmt = $this->executeStatement($query, $params);

        return $stmt->rowCount();
    }

    protected function executeStatement(string $query, array $params): PDOStatement
    {
        $stmt = $this->getConnection()->prepare($query);
        $stmt->execute($params);

        return $stmt;
    }

    public function insert(string $table, array $params): int
    {
        $params = array_filter($params);
        $columns = [];
        $values = [];
        $placeholders = [];

        foreach ($params as $column => $value) {
            $placeholder = ':' . $column;
            $columns[] = $column;
            $placeholders[] = $placeholder;
            $values[$placeholder] = $value;
        }

        return $this->execute(
            sprintf(
                'insert into "%s" (%s) values (%s)',
                $table,
                implode(',', $columns),
                implode(',', $placeholders)
            ),
            $values
        );
    }

    public function update(string $table, array $params): int
    {
        $params = array_filter($params);
        $values = [];
        $placeholders = [];

        foreach ($params as $column => $value) {
            $placeholders[] = sprintf('%s = :%s', $column, $column);
            $values[':' . $column] = $value;
        }

        return $this->execute(
            sprintf(
                'update "%s" set %s where id = :id',
                $table,
                implode(',', $placeholders)
            ),
            $values
        );
    }

    public function beginTransaction(): void
    {
        $this->getConnection()->beginTransaction();
    }

    public function commit(): void
    {
        $this->getConnection()->commit();
    }

    public function rollBack(): void
    {
        $this->getConnection()->rollBack();
    }

    public function getLastInsertId(string $name = null): string
    {
        return $this->getConnection()->lastInsertId($name);
    }

    public function escapeValue(string $value): string
    {
        return str_replace(
            ['\\', "\0", "\n", "\r", "'", '"', "\x1a"],
            ['\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'],
            $value
        );
    }

    protected function getConnection(): PDO
    {
        if (!$this->connection) {
            $this->createConnection($this->options);
        }

        return $this->connection;
    }

    protected function createConnection(array $options): void
    {
        $dsn = sprintf(
            'pgsql:host=%s;port=%s;dbname=%s;user=%s;password=%s',
            $options['host'],
            $options['port'],
            $options['dbname'],
            $options['username'],
            $options['password']
        );

        $driverOptions = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];

        if (isset($options['username']) && isset($options['password'])) {
            $this->connection = new PDO($dsn, $options['username'], $options['password'], $driverOptions);
        } else {
            $this->connection = new PDO($dsn, null, null, $driverOptions);
        }
    }
}
