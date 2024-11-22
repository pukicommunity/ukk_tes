<?php

use App\Models\Book;
use App\Models\Borrow;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
uses(RefreshDatabase::class);

beforeEach(function(){
    $this->adminUser = User::factory()->create(['role' => 'admin']);
    $this->normalUser = User::factory()->create(['role'=>'user']);
});

it('return borrows on index',function(){
    Borrow::factory()->count(3)->create();
    $response = $this->getJson('/admin/borrows');
    $response->assertStatus(200)->assertJsonStructure([
        '*' => [
            'user_id',
            'book_id',
            'borrow_date',
            'return_date'
        ]
    ]);
});