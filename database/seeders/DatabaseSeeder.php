<?php

namespace Database\Seeders;

use App\Models\Subscription;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Channel;
use App\Models\Video;
use App\Models\Comment;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $user1 = User::factory()->create([
            'email' => 'dragos@mail.com',
        ]);

        $user2 = User::factory()->create([
            'email' => 'petcu@mail.com',
        ]);

        $channel1 = Channel::factory()->create([
            'user_id' => $user1->id,
        ]);

        $channel2 = Channel::factory()->create([
            'user_id' => $user2->id,
        ]);

        $channel1->subscriptions()->create([
            'user_id' => $user2->id,
        ]);

        $channel2->subscriptions()->create([
            'user_id' => $user1->id,
        ]);

        Subscription::factory()->count(100)->create([
            'channel_id' => $channel1->id,
        ]);

        Subscription::factory()->count(100)->create([
            'channel_id' => $channel2->id,
        ]);

        $video = Video::factory()->create([
            'channel_id' => $channel1->id,
        ]);

        Comment::factory()->count(50)->create([
            'video_id' => $video->id,
        ]);

        $comment = Comment::first();

        Comment::factory()->count(50)->create([
            'video_id'   => $video->id,
            'comment_id' => $comment->id,
        ]);

    }
}
