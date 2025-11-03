<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Tests\Exceptions;

use AzahariZaman\Huggingface\Exceptions\TransporterException;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;

final class TransporterExceptionTest extends TestCase
{
    public function testConstructWithClientException(): void
    {
        $clientException = new class ('Client error') extends \Exception implements ClientExceptionInterface {
        };

        $exception = new TransporterException($clientException);

        $this->assertSame('Client error', $exception->getMessage());
        $this->assertSame($clientException, $exception->getPrevious());
    }
}
