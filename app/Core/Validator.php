<?php

namespace App\Core;

/**
 * اعتبارسنجی داده‌های فرم با قواعد ساده و پیام‌های فارسی.
 */
class Validator
{
    private array $data;
    private array $rules;
    public array $errors = [];

    public function __construct(array $data, array $rules)
    {
        $this->data = $data;
        $this->rules = $rules;
    }

    /**
     * قواعد موجود:
     * required, min:N, max:N, email, numeric, int, in:a,b,c, url, slug
     */
    public function passes(): bool
    {
        foreach ($this->rules as $field => $ruleString) {
            $rules = explode('|', $ruleString);
            $value = $this->data[$field] ?? null;
            $isRequired = in_array('required', $rules, true);

            // اگر فیلد خالی و اجباری نیست، از بررسی‌های دیگر بگذر
            $empty = ($value === null || $value === '' || (is_string($value) && trim($value) === ''));
            if ($empty && !$isRequired) {
                continue;
            }
            if ($empty && $isRequired) {
                $this->addError($field, 'این فیلد الزامی است.');
                continue;
            }

            foreach ($rules as $rule) {
                if ($rule === 'required') {
                    continue;
                }
                if (str_starts_with($rule, 'min:')) {
                    $min = (int) substr($rule, 4);
                    $len = is_string($value) ? mb_strlen(trim($value)) : (is_numeric($value) ? (float) $value : 0);
                    if ($len < $min) {
                        $this->addError($field, "مقدار باید حداقل {$min} کاراکتر باشد.");
                    }
                } elseif (str_starts_with($rule, 'max:')) {
                    $max = (int) substr($rule, 4);
                    $len = is_string($value) ? mb_strlen(trim($value)) : (is_numeric($value) ? (float) $value : 0);
                    if ($len > $max) {
                        $this->addError($field, "مقدار باید حداکثر {$max} کاراکتر باشد.");
                    }
                } elseif ($rule === 'email') {
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $this->addError($field, 'ایمیل وارد شده معتبر نیست.');
                    }
                } elseif ($rule === 'numeric') {
                    if (!is_numeric($value)) {
                        $this->addError($field, 'مقدار باید عددی باشد.');
                    }
                } elseif ($rule === 'int') {
                    if (filter_var($value, FILTER_VALIDATE_INT) === false) {
                        $this->addError($field, 'مقدار باید یک عدد صحیح باشد.');
                    }
                } elseif ($rule === 'url') {
                    if (!filter_var($value, FILTER_VALIDATE_URL)) {
                        $this->addError($field, 'نشانی اینترنتی معتبر نیست.');
                    }
                } elseif ($rule === 'slug') {
                    if (!preg_match('/^[a-zA-Z0-9\-_]+$/', (string) $value)) {
                        $this->addError($field, 'نامک فقط می‌تواند شامل حروف انگلیسی، اعداد، خط تیره و زیرخط باشد.');
                    }
                } elseif (str_starts_with($rule, 'in:')) {
                    $allowed = explode(',', substr($rule, 3));
                    if (!in_array((string) $value, $allowed, true)) {
                        $this->addError($field, 'مقدار انتخاب‌شده معتبر نیست.');
                    }
                }
            }
        }
        return empty($this->errors);
    }

    public function fails(): bool
    {
        return !$this->passes();
    }

    private function addError(string $field, string $message): void
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = $message;
        }
    }

    public static function make(array $data, array $rules): self
    {
        return new self($data, $rules);
    }
}
