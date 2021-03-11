<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Support\Str;

/**
 * Class Model
 * Specific Model to be extended for this app only
 *
 * @package App\Models
 * @project: laratube
 */
class Model extends BaseModel
{
    use HasFactory;

    /**
     * We are not guarding any model field
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The primary key should not be incremented because it's a string uui
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The primary key is a string so we should set the key type here
     * @var string
     */
    protected $keyType = 'string';

    /**
     * When a model is created assign the primary key to be an uuid
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }
}
