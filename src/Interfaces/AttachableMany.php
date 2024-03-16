<?php

namespace Akatekno\Attachable\Interfaces;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface AttachableMany
{
    public function attachments(): MorphMany;
}