<?php

namespace App\Http\Controllers;

use App\Jobs\Videos\ConvertForStreaming;
use App\Jobs\Videos\CreateVideoThumbnail;
use App\Models\Channel;

class UploadVideoController extends Controller
{
    /**
     * Show upload form for a video
     *
     * @param  Channel  $channel
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Channel $channel)
    {
        return view('channels.upload')->with('channel', $channel);
    }

    /**
     * Save / Upload a video and also trigger the conversion for the thumbnail and for the video streaming
     *
     * @param  Channel  $channel
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(Channel $channel)
    {
        $video = $channel->videos()->create([
            'title' => request()->title,
            'path'  => request()->video->store("channels/{$channel->id}"),
        ]);

        $this->dispatch(new CreateVideoThumbnail($video));

        $this->dispatch(new ConvertForStreaming($video));

        return $video;
    }
}
