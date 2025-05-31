<?php

namespace Tests\Feature\Traits;

use Illuminate\Support\Facades\Storage;

trait FakeStorage
{
    public function setUpFakeStorage(): void
    {
        Storage::fake('s3');
        Storage::fake('public');
        config(['filesystems.default' => 's3']);
    }
}
