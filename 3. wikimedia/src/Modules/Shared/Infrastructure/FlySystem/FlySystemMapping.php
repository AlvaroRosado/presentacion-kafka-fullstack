<?php

namespace App\Modules\Shared\Infrastructure\FlySystem;

enum FlySystemMapping: string
{
    case PUBLIC_UPLOADS = 'PUBLIC_UPLOADS';
    case PRIVATE_UPLOADS = 'PRIVATE_UPLOADS';
}
