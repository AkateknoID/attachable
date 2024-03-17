<?php

namespace Akatekno\Attachable\Traits;

use Akatekno\Attachable\Models\Attachment;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait AttachableOne
{
    public function attachment(): MorphOne
    {
        return $this->morphOne(Attachment::class, 'attachable');
    }
}