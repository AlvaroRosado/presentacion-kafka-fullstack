<?php

namespace App\Modules\Shared\Domain;

interface UrlResolver
{
    public function resolveAvatarUrl(string $filename): string;
    public function resolvePublicUrl(string $filename): string;
    public function resolvePrivateUrl(string $filename): string;
}
