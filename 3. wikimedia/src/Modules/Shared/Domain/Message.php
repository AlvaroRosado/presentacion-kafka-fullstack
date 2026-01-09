<?php

namespace App\Modules\Shared\Domain;

abstract class Message
{
    public string $occurredOn;
    protected ?string $messageId = null;
    protected ?string $causationId = null;
    protected ?string $correlationId = null;

    public function __construct(
        ?string $messageId = null,
        ?string $occurredOn = null,
    ) {
        $this->occurredOn = $occurredOn ?? new \DateTimeImmutable()->format(DATE_ATOM);
        $this->messageId = $messageId ?? MessageId::nextId()->toString();
        $this->asNew();
    }

    public function stampIds(
        ?MessageId $messageId = null,
        ?MessageId $causationId = null,
        ?MessageId $correlationId = null,
    ): static {
        $this->messageId = $messageId?->toString();
        $this->causationId = $causationId?->toString();
        $this->correlationId = $correlationId?->toString();

        return $this;
    }

    public function asNew(): static
    {
        $uuid = $this->messageId;

        if (null === $uuid) {
            $messageId = MessageId::nextId();
        } else {
            $messageId = MessageId::of($this->messageId);
        }

        return $this->stampIds(
            messageId: $messageId,
            causationId: null,
            correlationId: $messageId,
        );
    }

    public function asResponseTo(Message $message): static
    {
        return $this->asResponse(
            causationId: $message->messageId(),
            correlationId: $message->messageCorrelationId()
        );
    }

    public function messageId(): ?MessageId
    {
        return $this->messageId ? MessageId::of($this->messageId) : null;
    }

    public function messageCausationId(): ?MessageId
    {
        return $this->causationId ? MessageId::of($this->causationId) : null;
    }

    public function messageCorrelationId(): ?MessageId
    {
        return $this->correlationId ? MessageId::of($this->correlationId) : null;
    }

    private function asResponse(
        ?MessageId $causationId,
        ?MessageId $correlationId,
    ): static {
        return $this->stampIds(
            messageId: $this->messageId(),
            causationId: $causationId,
            correlationId: $correlationId,
        );
    }
}
