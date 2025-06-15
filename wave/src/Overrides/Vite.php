<?php

namespace Wave\Overrides;

use Illuminate\Foundation\Vite as BaseVite;

class Vite extends BaseVite
{
    public function __invoke($entrypoints, $buildDirectory = null)
    {
        return parent::__invoke($entrypoints, 'demo');
    }
}
