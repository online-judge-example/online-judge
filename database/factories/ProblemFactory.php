<?php

namespace Database\Factories;

use App\Models\Problem;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProblemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Problem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'setter_id' => $this->faker->randomDigitNotNull(),
            'title' => $this->faker->words(5),
            'description' => $this->faker->text(200),
            'input_format' => $this->faker->text(100),
            'output_format' => $this->faker->text(100),
            'time_limit' => $this->faker->randomDigitNotNull(),
            'memory_limit' => $this->faker->randomNumber(5, false),
            'sample_input' => $this->faker->text(20),
            'sample_output' => $this->faker->text(20),
            'execution_type' => '1',
        ];

    }
}
