<?php

namespace App\Modules\Shared\Infrastructure\FlySystem;

use App\Modules\Shared\Domain\StoreFile;
use App\Modules\Shared\Domain\TenantFilesystem;
use League\Flysystem\FilesystemOperator;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FlySystemTenantFileSystem implements TenantFilesystem
{
    public function __construct(
        private FilesystemOperator $privateUploadsFilesystem,
        private Security $security,
        private SluggerInterface $slugger,
    ) {
    }

    private function prefix(): string
    {
        return $this->security->getUser()?->getUserIdentifier() ?? 'anonymous';
    }

    private function path(?string $fileName = '', ?string $directory = null): string
    {
        $segments = array_filter([$this->prefix(), $directory, $fileName]);

        return implode('/', array_map(fn ($s) => trim($s, '/'), $segments));
    }

    public function storeUploadedFile(UploadedFile $file, ?string $directory = 'tmp'): StoreFile
    {
        $safeName = $this->slugger->slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $filename = sprintf('%s-%s.%s', $safeName, uniqid(), $file->guessExtension());
        $relativePath = $this->path($filename, $directory);

        $stream = fopen($file->getRealPath(), 'r');
        $this->privateUploadsFilesystem->writeStream($relativePath, $stream);
        fclose($stream);

        return new StoreFile(
            originalName: $file->getClientOriginalName(),
            storedName: $filename,
            path: $relativePath,
            size: $file->getSize(),
            mimeType: $file->getMimeType(),
        );
    }

    public function readPath(string $path): string
    {
        return $this->privateUploadsFilesystem->read($path);
    }

    public function readPathAsUploadedFile(string $path): UploadedFile
    {
        $content = $this->privateUploadsFilesystem->read($path);
        $tmpPath = tempnam(sys_get_temp_dir(), 'tenant_');
        file_put_contents($tmpPath, $content);

        $mimeType = mime_content_type($tmpPath) ?: 'application/octet-stream';
        $originalName = basename($path);

        return new UploadedFile(
            path: $tmpPath,
            originalName: $originalName,
            mimeType: $mimeType,
            error: null,
            test: true
        );
    }

    public function read(string $fileName, ?string $directory = null): string
    {
        return $this->privateUploadsFilesystem->read($this->path($fileName, $directory));
    }

    public function has(string $fileName, ?string $directory = null): bool
    {
        return $this->privateUploadsFilesystem->fileExists($this->path($fileName, $directory));
    }

    public function delete(string $fileName, ?string $directory = null): void
    {
        $path = $this->path($fileName, $directory);
        if ($this->privateUploadsFilesystem->fileExists($path)) {
            $this->privateUploadsFilesystem->delete($path);
        }
    }

    public function list(string $directory = ''): iterable
    {
        foreach ($this->privateUploadsFilesystem->listContents($this->path($directory)) as $item) {
            yield $item->path();
        }
    }

    public function write(string $path, mixed $content): void
    {
        $fullPath = $this->path($path);
        if (is_resource($content)) {
            $this->privateUploadsFilesystem->writeStream($fullPath, $content);
        } else {
            $this->privateUploadsFilesystem->write($fullPath, (string) $content);
        }
    }
}
