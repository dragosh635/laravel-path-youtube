<?php

namespace App\Jobs\Videos;

use App\Models\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use FFMpeg\Format\Video\X264;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

/**
 * Class ConvertForStreaming
 * Job Queue to convert video for streaming it online
 * Basically divide the video into smaller parts and also into 3 categories for different type of internet speed
 *
 * @package App\Jobs\Videos
 * @project: laratube
 */
class ConvertForStreaming implements ShouldQueue
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
        $low    = (new X264('aac'))->setKiloBitrate(100); //360p  slow interned speed
        $medium = (new X264('aac'))->setKiloBitrate(250); // medium internet speed
        $high   = (new X264('aac'))->setKiloBitrate(500); // fast internet speed

        /**
         * Take the video and generate parts for each of the low, medium, high
         */
        FFMpeg::fromDisk('local')
              ->open($this->video->path)
              ->exportForHLS()
              ->onProgress(function ($percentage) {
                  $this->video->update([
                      'percentage' => $percentage,
                  ]);
              })
              ->addFormat($low)
              ->addFormat($medium)
              ->addFormat($high)
              ->save("public/videos/{$this->video->id}.m3u8");
    }
}
