<?php

namespace App\Models;

use App\Core\Model;

/**
 * محتوای بخش‌های اصلی صفحه خانه (هیرو، معرفی، مزایا و ...).
 * هر بخش با یک section_key یکتا مشخص می‌شود.
 */
class HomeSection extends Model
{
    protected string $table = 'home_sections';
    protected array $fillable = [
        'section_key', 'title', 'subtitle', 'body', 'image', 'cta_text', 'cta_url',
        'is_published', 'sort_order', 'updated_at',
    ];

    public function findByKey(string $key): mixed
    {
        return \App\Core\Database::fetch("SELECT * FROM {$this->table} WHERE section_key = ? LIMIT 1", [$key]);
    }

    public function allByKey(): array
    {
        $rows = \App\Core\Database::fetchAll("SELECT * FROM {$this->table} ORDER BY sort_order ASC");
        $result = [];
        foreach ($rows as $row) {
            $result[$row['section_key']] = $row;
        }
        return $result;
    }

    /**
     * به‌روزرسانی یا ایجاد بخش بر اساس کلید.
     */
    public function upsertByKey(string $key, array $data): void
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $exists = $this->findByKey($key);
        if ($exists) {
            $update = $this->filterFillable($data);
            unset($update['section_key']);
            \App\Core\Database::update($this->table, $update, 'section_key = ?', ['section_key' => $key]);
        } else {
            $data['section_key'] = $key;
            $insert = $this->filterFillable($data);
            \App\Core\Database::insert($this->table, $insert);
        }
    }

    /**
     * مزایا/مراحل همکاری (آیتم‌های قابل مدیریت در بخش مزايا).
     */
    public function advantages(): array
    {
        return \App\Core\Database::fetchAll(
            "SELECT * FROM advantages WHERE is_published = 1 ORDER BY sort_order ASC, id ASC"
        );
    }
}
