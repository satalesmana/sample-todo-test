<?php

namespace Tests\Feature;

use App\Models\Todos;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;

class CommentTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCreteComment()
    {
        $user = User::first();
        $todos = Todos::first();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->actingAs($user)
        ->json('POST', '/api/comments', [
            'todos_id' => $todos->id,
            'body' => Str::random(10),
        ]);
        
        $response->dump();

        $response->assertStatus(200);
    }
}
