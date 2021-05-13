<?php

namespace Database\Factories\FbReporting;

use App\Models\FbReporting\FbPagePost;
use Illuminate\Database\Eloquent\Factories\Factory;

class FbPagePostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FbPagePost::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'text' =>  $this->faker->text,
            'url' => $this->faker->url,
            'reference' => $this->faker->name,
            'media' => ''
        ];
    }
}
