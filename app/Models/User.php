<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Primary key should not increment
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Primary key is an uuid, so its type should be a string
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Assign the primary key when the user is created
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }

    /**
     * A user can have one channel
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function channel()
    {
        return $this->hasOne(Channel::class);
    }

    /**
     * Vote up or vote down
     *
     * @param $entity - Can be a comment or a video
     * @param $type - up / down
     *
     * @return mixed
     */
    public function toggleVote($entity, $type)
    {
        $vote = $entity->votes->where('user_id', $this->id)->first();

        if ($vote) {
            $vote->update([
                'type' => $type,
            ]);

            return $vote->refresh();
        }

        return $entity->votes()->create([
            'type'    => $type,
            'user_id' => $this->id,
        ]);
    }

    /**
     * A user has many comments
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id', 'id');
    }
}
