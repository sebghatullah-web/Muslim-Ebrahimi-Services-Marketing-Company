<?php

namespace App\Models;

use App\Core\Model;

class PortfolioCategory extends Model
{
    protected string $table = 'portfolio_categories';
    protected array $fillable = ['name', 'slug', 'sort_order', 'is_published', 'created_at', 'updated_at'];

    public function published(): array
    {
        return \App\Core\Database::fetchAll(
            "SELECT * FROM {$this->table} WHERE is_published = 1 ORDER BY sort_order ASC, id ASC"
        );
    }
}
