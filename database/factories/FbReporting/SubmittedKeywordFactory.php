<?php

namespace Database\Factories\FbReporting;

use App\Models\FbReporting\SubmittedKeyword;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SubmittedKeywordFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SubmittedKeyword::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'batch_id' => Str::orderedUuid(),
            'keyword' => $this->faker->domainWord. ' ' . $this->faker->domainWord,
            'market' => $this->faker->randomElement(['DE', 'US', 'UK']),
            'status' =>  $this->faker->randomElement(['pending', 'processing', 'processed']),
            'action_taken' => $this->faker->randomElement(['new', 'skipped']),
            'note' => $this->faker->text
        ];
    }
}
