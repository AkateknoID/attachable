<?php

namespace Akatekno\Attachable\Traits;

use Akatekno\Attachable\Models\Attachment;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait AttachableMany
{
    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}