<?php

namespace App\Modules\Shared\Infrastructure\Symfony\Messenger;

use App\Modules\Shared\Domain\Message;
use ARO\KafkaMessenger\Transport\Hook\KafkaTransportHookInterface;
use ARO\KafkaMessenger\Transport\Stamp\KafkaIdentifierStamp;
use ARO\KafkaMessenger\Transport\Stamp\KafkaKeyStamp;
use Symfony\Component\Messenger\Envelope;
class EventStreammingHook implements KafkaTransportHookInterface
{
    public function beforeProduce(Envelope $envelope): Envelope
    {
        $message = $envelope->getMessage();
        $stamps = [];

        // Required for advanced mode: Add identifier for all Kafka messages
        if ($message instanceof Message) {
            $stamps[] = new KafkaIdentifierStamp($message->identifier());

            if ($message->key()) {
                $stamps[] = new KafkaKeyStamp($message->key());
            }
        }

        return $envelope->with(...$stamps);
    }

    public function afterProduce(Envelope $envelope): void
    {
        // Logging, metrics, etc.
    }

    public function beforeConsume(\RdKafka\Message $message): \RdKafka\Message
    {
        // Validation, transformation, etc.
        return $message;
    }

    public function afterConsume(Envelope $envelope): void
    {
        // Cleanup, final logging, etc.
    }
}
