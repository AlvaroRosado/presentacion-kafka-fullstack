<?php

namespace App\Modules\Shared\Infrastructure\Repository;

use App\Modules\Shared\Domain\Event;
use App\Modules\Shared\Domain\EventStoreRepository;
use App\Modules\Shared\Infrastructure\Serializer\JsonObjectEncoder;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineDbalEventStoreRepository implements EventStoreRepository
{
    public function __construct(
        private EntityManagerInterface $em,
        private JsonObjectEncoder $serializer,
    ) {
    }

    public function ofStream(string $stream, string $streamId, ?\DateTimeInterface $from = null): iterable
    {
        $query = 'SELECT * FROM event_store WHERE stream = :stream AND stream_id = :streamId';
        $params = ['stream' => $stream, 'streamId' => $streamId];

        if ($from) {
            $query .= ' AND created_at >= :from';
            $params['from'] = $from->format('Y-m-d H:i:s');
        }

        $query .= ' ORDER BY id';

        foreach ($this->em->getConnection()->iterateAssociative($query, $params) as $event) {
            $type = $event['type'];

            yield $this->serializer->decode($event['payload'], $type);
        }
    }

    public function add(
        Event $event,
    ): void {
        $streamId = $event->streamId();
        $stream = $event->streamName();
        $type = $event::class;
        $payload = $this->serializer->encode($event);
        $version = $event->version();

        $this->em->getConnection()->insert('event_store', [
            'stream' => $stream,
            'stream_id' => $streamId,
            'type' => $type,
            'payload' => $payload,
            'metadata' => '{}',
            'version' => $version,
            'created_at' => (new \DateTime())->format('Y-m-d H:i:s'),
        ]);
    }
}
