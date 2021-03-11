<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class Comment extends Model
{
    use HasFactory;

    /**
     * When a comment is loaded, always bring the associated user and votes
     *
     * @var string[]
     */
    protected $with = ['user', 'votes'];

    /**
     * When a user is loaded and sent over json, add also the properties from here
     *
     * @var string[]
     */
    protected $appends = ['repliesCount'];

    /**
     * A comment belongs to a video
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    /**
     * A comment belongs to a user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * A comment has many replies
     * We didn't do another table for replies but used the same one where the comments were saved
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'comment_id')->whereNotNull('comment_id');
    }

    /**
     * Return the number of replies for this comment
     *
     * @return mixed
     */
    public function getRepliesCountAttribute()
    {
        return $this->replies->count();
    }

    /**
     * A comment can have votes
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function votes()
    {
        return $this->morphMany(Vote::class, 'voteable');
    }
}
