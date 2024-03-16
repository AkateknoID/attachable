<?php

namespace Akatekno\Attachable\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attachment extends Model
{
    use HasUuids, HasFactory, SoftDeletes;

    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }

    protected $fillable = [
        'attachable_id',
        'attachable_type',
        'name',
        'path',
        'mime_type',
        'extension',
        'size',
        'type',
    ];
}