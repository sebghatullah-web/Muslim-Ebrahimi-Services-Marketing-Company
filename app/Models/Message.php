<?php

namespace App\Models;

use App\Core\Model;

class Message extends Model
{
    protected string $table = 'messages';
    protected array $fillable = [
        'name', 'email', 'phone', 'subject', 'message', 'is_read', 'created_at', 'updated_at',
    ];

    public function unreadCount(): int
    {
        return \App\Core\Database::count($this->table, 'is_read = 0');
    }

    public function latest(int $limit = 5): array
    {
        $limit = max(1, min(50, $limit));
        return \App\Core\Database::fetchAll(
            "SELECT * FROM {$this->table} ORDER BY created_at DESC, id DESC LIMIT $limit"
        );
    }


    public function markRead(int $id): void
    {
        \App\Core\Database::update($this->table, ['is_read' => 1], 'id = ?', ['id' => $id]);
    }

    public function markUnread(int $id): void
    {
        \App\Core\Database::update($this->table, ['is_read' => 0], 'id = ?', ['id' => $id]);
    }

    /**
     * صفحه‌بندی + جست‌وجو + فیلتر وضعیت خوانده‌شدن.
     */
    public function paginate(int $page, int $perPage, string $search = '', ?string $readFilter = null): array
    {
        $where = '1';
        $params = [];
        if ($search !== '') {
            $where .= " AND (name LIKE ? OR email LIKE ? OR phone LIKE ? OR subject LIKE ?)";
            for ($i = 0; $i < 4; $i++) {
                $params[] = "%$search%";
            }
        }
        if ($readFilter === 'read') {
            $where .= " AND is_read = 1";
        } elseif ($readFilter === 'unread') {
            $where .= " AND is_read = 0";
        }

        $total = (int) (\App\Core\Database::fetch("SELECT COUNT(*) AS c FROM {$this->table} WHERE $where", $params)['c'] ?? 0);
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT * FROM {$this->table} WHERE $where ORDER BY created_at DESC, id DESC LIMIT $perPage OFFSET $offset";
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
