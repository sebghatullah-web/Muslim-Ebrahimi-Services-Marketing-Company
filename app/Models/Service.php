<?php

namespace App\Models;

use App\Core\Model;

class Service extends Model
{
    protected string $table = 'services';
    protected array $fillable = [
        'title', 'slug', 'icon', 'image', 'summary', 'description',
        'seo_title', 'seo_description', 'sort_order', 'is_published', 'created_at', 'updated_at',
    ];

    /**
     * دریافت خدمات منتشرشده به ترتیب نمایش.
     */
    public function published(): array
    {
        return \App\Core\Database::fetchAll(
            "SELECT * FROM {$this->table} WHERE is_published = 1 ORDER BY sort_order ASC, id ASC"
        );
    }

    public function findBySlug(string $slug): mixed
    {
        return \App\Core\Database::fetch(
            "SELECT * FROM {$this->table} WHERE slug = ? LIMIT 1",
            [$slug]
        );
    }

    /**
     * بیشترین ترتیب نمایش فعلی + ۱
     */
    public function nextSortOrder(): int
    {
        $row = \App\Core\Database::fetch("SELECT MAX(sort_order) AS m FROM {$this->table}");
        return (int) ($row['m'] ?? 0) + 1;
    }
}
