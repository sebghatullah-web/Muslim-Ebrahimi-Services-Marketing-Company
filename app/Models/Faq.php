<?php

namespace App\Models;

use App\Core\Model;

class Faq extends Model
{
    protected string $table = 'faqs';
    protected array $fillable = [
        'question', 'answer', 'is_published', 'sort_order', 'created_at', 'updated_at',
    ];

    public function published(): array
    {
        return \App\Core\Database::fetchAll(
            "SELECT * FROM {$this->table} WHERE is_published = 1 ORDER BY sort_order ASC, id ASC"
        );
    }
}
