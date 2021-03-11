<?php

namespace App\Jobs\Videos;

use App\Models\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

/**
 * Class CreateVideoThumbnail
 * Generate video thumbnail in a queue in order to not hold the user until this happens
 *
 * @package App\Jobs\Videos
 * @project: laratube
 */
class CreateVideoThumbnail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $video;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /* The video thumbnail sizes are set in the controller */
        FFMpeg::fromDisk('local')
              ->open($this->video->path)
              ->getFrameFromSeconds(1)
              ->export()
              ->toDisk('local')
              ->save("public/thumbnails/{$this->video->id}.png");

        $this->video->update([
            'thumbnail' => Storage::url("public/thumbnails/{$this->video->id}.png"),
        ]);

    }
}
