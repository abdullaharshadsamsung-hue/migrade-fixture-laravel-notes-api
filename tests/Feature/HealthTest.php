<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\TestCase;

class HealthTest extends TestCase
{
    public function test_health_is_ok(): void
    {
        $this->getJson('/health')->assertOk()->assertJson(['status' => 'ok']);
    }

    public function test_a_note_needs_a_title(): void
    {
        $this->postJson('/notes', ['body' => 'no title'])->assertStatus(400);
    }
}
