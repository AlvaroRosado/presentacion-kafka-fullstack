<?php

namespace App\Modules\Shared\Application;

interface QueryBus
{
    public function ask(Query $message): mixed;
}
