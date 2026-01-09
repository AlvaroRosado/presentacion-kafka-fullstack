<?php

namespace App\Modules\Shared\Domain;

abstract class AggregateRoot
{
    /** @var list<Event> */
    private array $domainEvents = [];

    /**
     * @return list<Event>
     */
    final public function pullDomainEvents(): array
    {
        $domainEvents = $this->domainEvents;
        $this->domainEvents = [];

        return $domainEvents;
    }

    final protected function record(Event $event): void
    {
        $this->domainEvents[] = $event;
    }

    /**
     * @throws BusinessRuleValidationException
     */
    final public function checkRule(BusinessRule $rule): void
    {
        if ($rule->isBroken()) {
            throw new BusinessRuleValidationException($rule);
        }
    }
}
