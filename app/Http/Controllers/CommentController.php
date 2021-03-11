<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
use App\Models\Comment;

class CommentController extends Controller
{
    /**
     * Return all the comments from a specific video
     *
     * @param  Video  $video
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(Video $video)
    {
        return $video->comments()->paginate(10);
    }

    /**
     * Return all the comments replies
     *
     * @param  Comment  $comment
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function show(Comment $comment)
    {
        return $comment->replies()->paginate(10);
    }

    /**
     * Save a comment
     *
     * @param  Request  $request
     * @param  Video  $video
     *
     * @return mixed
     */
    public function store(Request $request, Video $video)
    {
        return auth()->user()->comments()->create([
            'body'       => $request->body,
            'comment_id' => $request->comment_id,
            'video_id'   => $video->id,
        ])->fresh();
        // fresh takes a new instance of this model from the db, after it was saved to make user that we are returning the right data back
    }
}
