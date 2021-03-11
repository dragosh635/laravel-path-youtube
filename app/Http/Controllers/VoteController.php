<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Video;

class VoteController extends Controller
{
    /**
     * Vote for a comment or a video
     *
     * @param string $entityId - Comment id or Video id
     * @param string $type - up / down
     *
     * @return mixed
     */
    public function vote($entityId, $type)
    {
        $entity = $this->getEntity($entityId);

        return auth()->user()->toggleVote($entity, $type);
    }

    /**
     * Return a video or a comment based on id
     *
     * @param string $entityId
     *
     * @return mixed
     */
    public function getEntity($entityId)
    {
        $video = Video::find($entityId);

        if ($video) {
            return $video;
        }

        $comment = Comment::find($entityId);

        if ($comment) {
            return $comment;
        }

        throw new ModelNotFoundException('Entity Not Found');
    }
}
