<?php

namespace App\Modules\Shared\Domain;

final readonly class StoreFile
{
    public function __construct(
        public string $originalName,
        public string $storedName,
        public string $path,
        public int $size,
        public string $mimeType,
    ) {
    }
}
