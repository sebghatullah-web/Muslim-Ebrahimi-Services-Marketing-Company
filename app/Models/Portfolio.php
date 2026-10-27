<?php

namespace App\Models;

use App\Core\Model;

class Portfolio extends Model
{
    protected string $table = 'portfolio_items';
    protected array $fillable = [
        'category_id', 'title', 'slug', 'client_name', 'image', 'summary', 'description',
        'project_url', 'seo_title', 'seo_description', 'sort_order', 'is_published', 'created_at', 'updated_at',
    ];

    /**
     * پروژه‌های منتشرشده همراه با نام دسته‌بندی.
     */
    public function published(?int $categoryId = null): array
    {
        $sql = "SELECT p.*, c.name AS category_name, c.slug AS category_slug
                FROM {$this->table} p
                LEFT JOIN portfolio_categories c ON c.id = p.category_id
                WHERE p.is_published = 1";
        $params = [];
        if ($categoryId !== null) {
            $sql .= " AND p.category_id = ?";
            $params[] = $categoryId;
        }
        $sql .= " ORDER BY p.sort_order ASC, p.id DESC";
        return \App\Core\Database::fetchAll($sql, $params);
    }

    public function findBySlug(string $slug): mixed
    {
        return \App\Core\Database::fetch(
            "SELECT p.*, c.name AS category_name
             FROM {$this->table} p
             LEFT JOIN portfolio_categories c ON c.id = p.category_id
             WHERE p.slug = ? LIMIT 1",
            [$slug]
        );
    }

    public function nextSortOrder(): int
    {
        $row = \App\Core\Database::fetch("SELECT MAX(sort_order) AS m FROM {$this->table}");
        return (int) ($row['m'] ?? 0) + 1;
    }

    /**
     * لیست صفحه‌بندی‌شده همراه با دسته‌بندی برای پنل مدیریت.
     */
    public function paginate(int $page, int $perPage, string $search = '', ?int $categoryId = null): array
    {
        $where = '1';
        $params = [];
        if ($search !== '') {
            $where .= " AND (p.title LIKE ? OR p.client_name LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        if ($categoryId !== null) {
            $where .= " AND p.category_id = ?";
            $params[] = $categoryId;
        }

        $total = (int) (\App\Core\Database::fetch(
            "SELECT COUNT(*) AS c FROM {$this->table} p WHERE $where",
            $params
        )['c'] ?? 0);

        $offset = ($page - 1) * $perPage;
        $sql = "SELECT p.*, c.name AS category_name
                FROM {$this->table} p
                LEFT JOIN portfolio_categories c ON c.id = p.category_id
                WHERE $where
                ORDER BY p.sort_order ASC, p.id DESC
                LIMIT $perPage OFFSET $offset";
        $rows = \App\Core\Database::fetchAll($sql, $params);

        return [
            'data'  => $rows,
            'total' => $total,
            'page'  => $page,
            'perPage' => $perPage,
            'lastPage' => max(1, (int) ceil($total / $perPage)),
        ];
    }
}
