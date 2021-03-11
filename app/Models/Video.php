<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Video extends Model
{
    use HasFactory;

    /**
     * A video belongs to a channel
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    /**
     * Check if the authenticated user can edit the video ( only if he is the owner of the channel where the vide resides )
     *
     * @return bool
     */
    public function editable()
    {
        return auth()->check() && $this->channel->user_id === auth()->user()->id;
    }

    /**
     * A video can have votes
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function votes()
    {
        return $this->morphMany(Vote::class, 'voteable');
    }

    /**
     * A has many comments
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class)
                    ->whereNull('comment_id')
                    ->orderBy('created_at', 'DESC');
    }
}
