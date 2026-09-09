<?php

namespace Tests\Feature;

use Tests\TestCase;

class WebsiteTest extends TestCase
{
    public function test_workspace_is_available(): void
    {
        $this->get('/notes')->assertOk()->assertSee('My notes')->assertSee('Export all notes');
    }

    public function test_waitlist_rejects_invalid_email_and_plan(): void
    {
        $this->postJson('/waitlist', ['email' => 'invalid', 'plan' => 'Unknown'])
            ->assertUnprocessable()->assertJsonValidationErrors(['email', 'plan']);
    }

    public function test_waitlist_saves_valid_interest(): void
    {
        $temporaryStorage = sys_get_temp_dir().DIRECTORY_SEPARATOR.'easynote-test-'.uniqid();
        mkdir($temporaryStorage.DIRECTORY_SEPARATOR.'app', 0777, true);
        $originalStorage = $this->app->storagePath();
        $this->app->useStoragePath($temporaryStorage);

        try {
            $this->postJson('/waitlist', ['email' => 'writer@example.com', 'plan' => 'Plus'])->assertCreated();
            $entry = json_decode(file_get_contents($temporaryStorage.'/app/waitlist.jsonl'), true);
            $this->assertSame('writer@example.com', $entry['email']);
            $this->assertSame('Plus', $entry['plan']);
        } finally {
            $this->app->useStoragePath($originalStorage);
            if (file_exists($temporaryStorage.'/app/waitlist.jsonl')) {
                unlink($temporaryStorage.'/app/waitlist.jsonl');
            }
            rmdir($temporaryStorage.'/app');
            rmdir($temporaryStorage);
        }
    }
}
