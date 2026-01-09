<?php

namespace App\Modules\Shared\Infrastructure\FlySystem;

use App\Modules\Shared\Domain\UrlResolver;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class FlySystemUrlResolver implements UrlResolver
{
    public function __construct(
        private ParameterBagInterface $params,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function resolveAvatarUrl(string $filename): string
    {
        return $this->resolvePublicUrl('avatars/'.$filename);
    }

    public function resolvePublicUrl(string $filename): string
    {
        $baseUrl = rtrim($this->params->get('uploads_base_url'), '/');

        return sprintf('%s/%s', $baseUrl, ltrim($filename, '/'));
    }

    public function resolvePrivateUrl(string $filename): string
    {
        return $this->urlGenerator->generate('private_file_viewer', [
            'filename' => ltrim($filename, '/'),
        ], referenceType: UrlGeneratorInterface::ABSOLUTE_URL);
    }
}
