<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    public function test_notifications_contains_empty_records(): void
    {
        $response = $this->get('/api/notifications/');

        $response->assertStatus(200);
        $response->assertSee('no notifications to get ');
    }
}
