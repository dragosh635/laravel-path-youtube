<?php

namespace App\Http\Controllers;

use App\Http\Requests\Videos\UpdateVideoRequest;
use App\Models\Video;

class VideoController extends Controller
{
    /**
     * Show a view on a template or return it as a json for vue
     *
     * @param  Video  $video
     *
     * @return Video|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(Video $video)
    {
        if (request()->wantsJson()) {
            return $video;
        }

        return view('videos/show', compact('video'));
    }

    /**
     * Update the number of views for this video
     *
     * @param  Video  $video
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateViews(Video $video)
    {
        $video->increment('views');

        return response()->json([]);
    }

    /**
     * Update title, description for the video
     *
     * @param  UpdateVideoRequest  $request
     * @param  Video  $video
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateVideoRequest $request, Video $video)
    {
        $video->update($request->only(['title', 'description']));

        return redirect()->back();
    }
}
