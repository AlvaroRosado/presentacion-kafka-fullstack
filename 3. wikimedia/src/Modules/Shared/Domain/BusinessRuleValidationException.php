<?php

namespace App\Modules\Shared\Domain;

class BusinessRuleValidationException extends \DomainException
{
    private $brokenRule;
    private $details;

    public function __construct(BusinessRule $brokenRule)
    {
        parent::__construct($brokenRule->message());
        $this->brokenRule = $brokenRule;
        $this->details = $brokenRule->message();
    }

    public function brokenRule(): BusinessRule
    {
        return $this->brokenRule;
    }

    public function details(): string
    {
        return $this->details;
    }

    public function __toString(): string
    {
        return get_class($this->brokenRule).': '.$this->brokenRule->message();
    }
}
