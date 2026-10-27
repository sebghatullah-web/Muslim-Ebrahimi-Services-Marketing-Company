<?php

namespace App\Models;

use App\Core\Model;

/**
 * مدیریت حساب مدیر. گذرواژه‌ها همیشه به‌صورت هش ذخیره می‌شوند.
 */
class User extends Model
{
    protected string $table = 'admin_users';
    protected array $fillable = ['name', 'email', 'password_hash', 'created_at', 'updated_at'];

    public function findByEmail(string $email): mixed
    {
        return \App\Core\Database::fetch("SELECT * FROM {$this->table} WHERE email = ? LIMIT 1", [$email]);
    }

    public function emailExists(string $email, ?int $exceptId = null): bool
    {
        if ($exceptId !== null) {
            $row = \App\Core\Database::fetch("SELECT id FROM {$this->table} WHERE email = ? AND id != ? LIMIT 1", [$email, $exceptId]);
        } else {
            $row = \App\Core\Database::fetch("SELECT id FROM {$this->table} WHERE email = ? LIMIT 1", [$email]);
        }
        return (bool) $row;
    }

    public function createUser(string $name, string $email, string $password): int
    {
        return \App\Core\Database::insert($this->table, [
            'name' => $name,
            'email' => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function changePassword(int $id, string $newPassword): void
    {
        \App\Core\Database::update($this->table, [
            'password_hash' => password_hash($newPassword, PASSWORD_DEFAULT),
            'updated_at' => date('Y-m-d H:i:s'),
        ], 'id = ?', ['id' => $id]);
    }

    public function verifyPassword(int $id, string $password): bool
    {
        $row = \App\Core\Database::fetch("SELECT password_hash FROM {$this->table} WHERE id = ? LIMIT 1", [$id]);
        return $row && password_verify($password, $row['password_hash']);
    }
}
