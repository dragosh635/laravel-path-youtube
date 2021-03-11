<?php

namespace Database\Factories;

use App\Models\Video;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class CommentFactory
 * Create random comments
 *
 * @package Database\Factories
 * @project: laratube
 */
class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'body'       => $this->faker->sentence(6),
            'user_id'    => function () {
                return User::factory()->create()->id;
            },
            'video_id'   => function () {
                return Video::factory()->create()->id;
            },
            'comment_id' => null,
        ];
    }
}
