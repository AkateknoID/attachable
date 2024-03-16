<?php

namespace Akatekno\Attachable\Interfaces;

use Illuminate\Database\Eloquent\Relations\MorphOne;

interface AttachableOne
{
    public function attachment(): MorphOne;
}