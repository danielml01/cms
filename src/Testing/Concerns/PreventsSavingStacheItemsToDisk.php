<?php

namespace Statamic\Testing\Concerns;

use Statamic\Facades\Path;
use Statamic\Facades\Stache;
use Statamic\Support\Str;

trait PreventsSavingStacheItemsToDisk
{
    protected function preventSavingStacheItemsToDisk(): void
    {
        $this->fakeStacheDirectory = Path::tidy($this->fakeStacheDirectory);

        Stache::stores()->each(function ($store) {
            $dir = Path::tidy(Str::before($this->fakeStacheDirectory, '/dev-null'));
            $relative = Str::after(Str::after($store->directory(), $dir), '/');
            $store->directory($this->fakeStacheDirectory.'/'.$relative);
        });
    }

    protected function deleteFakeStacheDirectory(): void
    {
        app('files')->deleteDirectory($this->fakeStacheDirectory);

        mkdir($this->fakeStacheDirectory);
        touch($this->fakeStacheDirectory.'/.gitkeep');
    }
}
