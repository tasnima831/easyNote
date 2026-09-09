<?php

namespace App\Services\Waitlist;

class WaitlistStorage
{
    /** @param array{email: string, plan: string} $attributes */
    public function append(array $attributes): bool
    {
        $entry = json_encode([...$attributes, 'created_at' => now()->toIso8601String()], JSON_THROW_ON_ERROR).PHP_EOL;

        return file_put_contents(storage_path('app/waitlist.jsonl'), $entry, FILE_APPEND | LOCK_EX) !== false;
    }
}
