<?php

namespace App\Models;

use App\Core\Model;

class Setting extends Model
{
    protected string $table = 'settings';
    protected array $fillable = ['setting_key', 'setting_value', 'setting_group', 'updated_at'];

    /**
     * به‌روزرسانی یا ایجاد یک تنظیم.
     */
    public function set(string $key, string $value, string $group = 'general'): void
    {
        $exists = \App\Core\Database::fetch('SELECT id FROM settings WHERE setting_key = ? LIMIT 1', [$key]);
        if ($exists) {
            \App\Core\Database::update($this->table, [
                'setting_value' => $value,
                'setting_group' => $group,
                'updated_at' => date('Y-m-d H:i:s'),
            ], 'setting_key = ?', ['setting_key' => $key]);
        } else {
            \App\Core\Database::insert($this->table, [
                'setting_key' => $key,
                'setting_value' => $value,
                'setting_group' => $group,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }

    /**
     * به‌روزرسانی گروهی تنظیمات از آرایه key=>value.
     */
    public function setMany(array $data, string $group = 'general'): void
    {
        foreach ($data as $key => $value) {
            $this->set($key, (string) $value, $group);
        }
    }

    public function getByGroup(string $group): array
    {
        $rows = \App\Core\Database::fetchAll('SELECT setting_key, setting_value FROM settings WHERE setting_group = ?', [$group]);
        $result = [];
        foreach ($rows as $row) {
            $result[$row['setting_key']] = $row['setting_value'];
        }
        return $result;
    }
}
