<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Enums\Transporter;

/**
 * @internal
 */
enum ContentType: string
{
    case JSON = 'application/json';
    case MULTIPART = 'multipart/form-data';
}
