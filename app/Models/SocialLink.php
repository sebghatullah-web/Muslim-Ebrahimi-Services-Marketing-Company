<?php

namespace App\Models;

use App\Core\Model;

class SocialLink extends Model
{
    protected string $table = 'social_links';
    protected array $fillable = [
        'platform', 'label', 'url', 'icon', 'is_published', 'sort_order', 'created_at', 'updated_at',
    ];

    public function published(): array
    {
        return \App\Core\Database::fetchAll(
            "SELECT * FROM {$this->table} WHERE is_published = 1 ORDER BY sort_order ASC, id ASC"
        );
    }
}
