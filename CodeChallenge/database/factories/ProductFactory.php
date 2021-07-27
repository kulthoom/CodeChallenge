<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'sku' => $this->faker->randomNumber(8),
            'price' => $this->faker->randomFloat(6),
            'currency' =>$this->faker->currencyCode ,
            'quantity' => $this->faker->randomNumber(6),
            'status' => $this->faker->text(15),
        ];
    }
}
