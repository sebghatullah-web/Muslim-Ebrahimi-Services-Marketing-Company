<?php

namespace App\Core;

/**
 * مدل پایه — عملیات CRUD عمومی روی یک جدول.
 * مدل‌های فرزند نام جدول و ستون‌های مجاز را مشخص می‌کنند.
 */
abstract class Model
{
    protected string $table = '';
    protected array $fillable = [];

    public function all(string $orderBy = 'id', string $dir = 'ASC'): array
    {
        $orderBy = $this->safeColumn($orderBy);
        $dir = strtoupper($dir) === 'DESC' ? 'DESC' : 'ASC';
        return Database::fetchAll("SELECT * FROM `{$this->table}` ORDER BY `$orderBy` $dir");
    }

    public function find(int $id): mixed
    {
        return Database::fetch("SELECT * FROM `{$this->table}` WHERE id = ? LIMIT 1", [$id]);
    }

    public function count(string $where = '1', array $params = []): int
    {
        return Database::count($this->table, $where, $params);
    }


    public function create(array $data): int
    {
        $data = $this->filterFillable($data);
        return Database::insert($this->table, $data);
    }

    public function update(int $id, array $data): bool
    {
        $data = $this->filterFillable($data);
        if (empty($data)) {
            return false;
        }
        return Database::update($this->table, $data, 'id = ?', ['id' => $id]) >= 0;
    }

    public function delete(int $id): bool
    {
        return Database::delete($this->table, 'id = ?', ['id' => $id]) > 0;
    }

    protected function filterFillable(array $data): array
    {
        return array_intersect_key($data, array_flip($this->fillable));
    }

    /**
     * جلوگیری از تزریق ستون در ORDER BY.
     */
    protected function safeColumn(string $col): string
    {
        return in_array($col, $this->fillable, true) || preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $col)
            ? $col
            : 'id';
    }
}
