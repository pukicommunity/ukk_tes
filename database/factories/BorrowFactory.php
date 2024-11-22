<?php

namespace Database\Factories;

use App\Models\Borrow;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Borrow>
 */
class BorrowFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Borrow::class;
    public function definition(): array
    {
        return [
            'book_id' => \App\Models\Book::factory()->create(),
            'user_id' => \App\Models\User::factory()->create(),
            'borrow_date' => now(),
            'return_date' => Carbon::tomorrow()
        ];
    }
}
