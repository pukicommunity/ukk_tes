<?php

use App\Models\Book;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->normalUser = User::factory()->create(['role' => 'user']);
    $this->adminUser = User::factory()->create(['role' => 'admin']);
    $this->category = Category::factory()->create();
});

it('returns books with pagination', function () {
    Book::factory()->count(5)->create();
    $response = $this->getJson('/api/books');
    $response->assertStatus(JsonResponse::HTTP_OK)
        ->assertJsonStructure([
            'current_page',
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'author',
                    'publisher',
                    'year',
                    'category_id',
                    'status'
                ]
            ]
        ]);
});
it('create a book', function () {
    $bookData = [
        'title' => 'Test Book',
        'author' => 'Test Author',
        'publisher' => 'Test Publisher',
        'year' => 2023,
        'category_id' => $this->category->id,
    ];

    $response = $this->actingAs($this->adminUser)->postJson('/api/books', $bookData);

    $response->assertStatus(JsonResponse::HTTP_CREATED)
             ->assertJsonFragment(['title' => 'Test Book']);

    $this->assertDatabaseHas('books', ['title' => 'Test Book']);
});

it('update a book', function() {
    $book = Book::factory()->create([
        'title' => 'original',
        'author' => 'original author',
        'publisher' => 'original publisher',
        'year' => 2014,
        'category_id' => $this->category->id
    ]);
    $newCategory = Category::factory()->create(['name' => 'New Category']);
    $updatedData = [
        'title' => 'updated',
        'author' => 'updated author',
        'publisher' => 'updated publisher',
        'year' => 2015,
        'category_id' => $newCategory->id
    ];
    $response = $this->actingAs($this->adminUser)->putJson("/api/books/{$book->id}", $updatedData);
    $response->assertStatus(JsonResponse::HTTP_OK)->assertJsonFragment(['title'=>'updated']);
    $this->assertDatabaseHas('books',[
        'id' => $book->id,
    ]);
});

it('delete a book', function(){
    $book = Book::factory()->create();
    $response = $this->actingAs($this->adminUser)->deleteJson("/api/books/$book->id");
    $response->assertStatus(JsonResponse::HTTP_NO_CONTENT);
    $this->assertDatabaseMissing('books',['id' => $book->id]);
});