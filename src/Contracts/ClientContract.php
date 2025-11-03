<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Contracts;

use AzahariZaman\Huggingface\Resources\Inference;

interface ClientContract
{
    public function inference(): Inference;
}
