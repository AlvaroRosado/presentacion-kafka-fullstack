<?php

namespace App\Modules\Shared\Domain;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface TenantFilesystem
{
    public function storeUploadedFile(UploadedFile $file, ?string $directory = 'tmp'): StoreFile;

    public function write(string $path, mixed $content): void;

    public function read(string $fileName, ?string $directory = null): string;
    public function readPath(string $path): string;
    public function readPathAsUploadedFile(string $path): UploadedFile;

    public function has(string $fileName, ?string $directory = null): bool;

    public function delete(string $fileName, ?string $directory = null): void;

    public function list(string $directory = ''): iterable;
}
