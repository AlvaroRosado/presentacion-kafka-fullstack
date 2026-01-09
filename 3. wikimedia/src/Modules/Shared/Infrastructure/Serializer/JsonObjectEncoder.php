<?php

namespace App\Modules\Shared\Infrastructure\Serializer;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeZoneNormalizer;
use Symfony\Component\Serializer\Normalizer\JsonSerializableNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer as SymfonySerializer;

class JsonObjectEncoder
{
    public ?SymfonySerializer $serializer = null;

    public function encode(object $data): string
    {
        return $this->createDefaultSerializer()->serialize($data, 'json');
    }

    public function decode(string $data, string $class): mixed
    {
        return $this->createDefaultSerializer()->deserialize($data, $class, 'json');
    }

    private function createDefaultSerializer(): SymfonySerializer
    {
        if (!$this->serializer) {
            $this->serializer = new SymfonySerializer(
                [
                    new JsonSerializableNormalizer(),
                    new DateTimeZoneNormalizer(),
                    new DateTimeNormalizer(),
                    new PropertyNormalizer(),
                    new ObjectNormalizer(),
                ],
                [new JsonEncoder()]
            );
        }

        return $this->serializer;
    }
}
