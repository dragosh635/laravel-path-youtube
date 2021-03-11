<?php

namespace Database\Factories;

use App\Models\Channel;
use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class VideoFactory
 * Create random videos
 *
 * @package Database\Factories
 * @project: laratube
 */
class VideoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Video::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'channel_id'  => function () {
                return Channel::factory()->create()->id;
            },
            'views'       => $this->faker->numberBetween(1, 1000),
            'thumbnail'   => $this->faker->imageUrl(),
            'percentage'  => 100,
            'title'       => $this->faker->sentence(4),
            'description' => $this->faker->sentence(10),
            'path'        => $this->faker->word(),
        ];
    }
}
