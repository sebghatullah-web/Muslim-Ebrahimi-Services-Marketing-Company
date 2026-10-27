<?php

namespace App\Models;

use App\Core\Model;

/**
 * مراحل همکاری / مزایای انتخاب شرکت.
 */
class Advantage extends Model
{
    protected string $table = 'advantages';
    protected array $fillable = [
        'title', 'description', 'icon', 'is_published', 'sort_order', 'created_at', 'updated_at',
    ];

    public function published(): array
    {
        return \App\Core\Database::fetchAll(
            "SELECT * FROM {$this->table} WHERE is_published = 1 ORDER BY sort_order ASC, id ASC"
        );
    }

    public function nextSortOrder(): int
    {
        $row = \App\Core\Database::fetch("SELECT MAX(sort_order) AS m FROM {$this->table}");
        return (int) ($row['m'] ?? 0) + 1;
    }
}
