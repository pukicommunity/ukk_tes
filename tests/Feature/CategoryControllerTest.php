<?php

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;

uses(RefreshDatabase::class);

beforeEach(function(){
    $this->normalUser = User::factory()->create(['role' => 'user']);
    $this->adminUser = User::factory()->create(['role' => 'admin']);
});

it('returns all categories', function (){
    Category::factory()->count(5)->create();
    $response = $this->getJson('/api/categories');
    $response->assertStatus(JsonResponse::HTTP_OK)->assertJsonStructure([
        '*' => [
            'id',
            'name'
        ]
    ]);
});

it('create a category', function (){
    $categoryData = [
        'name' => 'tes'
    ];
    $response = $this->actingAs($this->adminUser)->postJson('/api/categories', $categoryData);
    $response->assertStatus(JsonResponse::HTTP_CREATED)->assertJsonFragment(['name' => 'tes']);
    $this->assertDatabaseHas('categories',['name' => 'tes']);
});

it('update a category', function(){
    $category = Category::factory()->create(['name'=>'original']);
    $updatedData = [
        'name' => 'updated'
    ];
    $response = $this->actingAs($this->adminUser)->putJson("/api/categories/{$category->id}", $updatedData);
    $response->assertStatus(JsonResponse::HTTP_OK)->assertJsonFragment(['name' => 'updated']);
    $this->assertDatabaseHas('categories',['name'=>'updated']);
});

it('deletes a category', function(){
    $category = Category::factory()->create();
    $response = $this->actingAs($this->adminUser)->deleteJson("/api/categories/{$category->id}");
    $response->assertStatus(JsonResponse::HTTP_NO_CONTENT);
    $this->assertDatabaseMissing('categories',['id'=>$category->id]);
});

it('forbids normal user from creating categories', function(){
    $categoryData = [
        'name' => 'categories'
    ];
    $response = $this->actingAs($this->normalUser)->postJson('/api/categories',$categoryData);
    $response->assertStatus(JsonResponse::HTTP_FORBIDDEN)->assertJson(['error' => 'You are not allowed to add category']);
});
