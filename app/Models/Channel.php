<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Channel extends Model implements HasMedia
{
    use HasMediaTrait;

    /**
     * The relatipships that should always be loaded
     *
     * When the Channel is loaded from the db, it will always take also the associated user
     *
     * @var string[]
     */
    protected $with = ['user'];

    /**
     * A channel belongs to a user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Conversion for channel thumbnail with the help of the media library
     *
     * @param  Media|null  $media
     *
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     */
    public function registerMediaConversions(?Media $media = null)
    {
        $this->addMediaConversion('thumb')
             ->width(100)
             ->height(100);
    }

    /**
     * Return the thumbnail url
     *
     * @return |null
     */
    public function image()
    {
        if ($this->media->first()) {
            return $this->media->first()->getFullUrl('thumb');
        }

        return null;
    }

    /**
     * Check if this channel is editable. Only if the logged in user is also the owner of the channel
     *
     * @return bool
     */
    public function editable()
    {
        return auth()->check() ? $this->user_id === auth()->user()->id : false;
    }

    /**
     * A channel has many subcriptions / subscribers
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * On a channel, there can be more than one video
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function videos()
    {
        return $this->hasMany(Video::class);
    }
}
