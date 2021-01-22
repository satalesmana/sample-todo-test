<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TodoTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $user = User::first();
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->actingAs($user)
        ->get('/api/todos');
        $response->dump();

        $response->assertStatus(200);
    }
}
