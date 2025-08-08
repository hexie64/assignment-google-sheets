<?php

namespace Database\Factories;

use App\Models\SheetRow;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SheetRow>
 */
class SheetsDataFactory extends Factory
{
    protected static array $columnDictionary = [
        'identifier',
        'name',
        'email',
        'status',
        'user_id',
        'title',
        'slug',
        'description',
        'price',
        'quantity',
        'is_active',
    ];

    protected static array $columns = [];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $data = [];
        foreach (static::$columns as $column) {
            $data[$column] = $this->faker->realTextBetween('10', '18');
        }

        return [
            'data' => $data
        ];
    }

    /**
     * @return Factory|SheetsDataFactory
     */
    public function columns(): Factory|SheetsDataFactory
    {
        $randomKeys = array_rand(static::$columnDictionary, 5);
        static::$columns = array_map(fn($key) => static::$columnDictionary[$key], $randomKeys);
        return $this->state([]);
    }
}
