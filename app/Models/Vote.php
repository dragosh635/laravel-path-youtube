<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Vote
 *
 * @package App\Models
 * @project: laratube
 */
class Vote extends Model
{
    use HasFactory;

    /**
     * Used for the morph relationships between videos, comments, votes
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function voteable()
    {
        return $this->morphTo();
    }
}
