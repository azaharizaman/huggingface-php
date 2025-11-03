<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Tests\ValueObjects;

use AzahariZaman\Huggingface\ValueObjects\ResourceUri;
use PHPUnit\Framework\TestCase;

final class ResourceUriTest extends TestCase
{
    public function testCreateReturnsResourceUri(): void
    {
        $uri = ResourceUri::create('models');
        
        $this->assertInstanceOf(ResourceUri::class, $uri);
        $this->assertSame('models', $uri->toString());
    }

    public function testUploadReturnsResourceUri(): void
    {
        $uri = ResourceUri::upload('files');
        
        $this->assertInstanceOf(ResourceUri::class, $uri);
        $this->assertSame('files', $uri->toString());
    }

    public function testListReturnsResourceUri(): void
    {
        $uri = ResourceUri::list('datasets');
        
        $this->assertInstanceOf(ResourceUri::class, $uri);
        $this->assertSame('datasets', $uri->toString());
    }

    public function testRetrieveReturnsResourceUri(): void
    {
        $uri = ResourceUri::retrieve('models', 'model-id', '/files');
        
        $this->assertInstanceOf(ResourceUri::class, $uri);
        $this->assertSame('models/model-id/files', $uri->toString());
    }

    public function testRetrieveWithEmptySuffix(): void
    {
        $uri = ResourceUri::retrieve('models', 'model-id', '');
        
        $this->assertSame('models/model-id', $uri->toString());
    }

    public function testRetrieveContentReturnsResourceUri(): void
    {
        $uri = ResourceUri::retrieveContent('files', 'file-id');
        
        $this->assertInstanceOf(ResourceUri::class, $uri);
        $this->assertSame('files/file-id/content', $uri->toString());
    }

    public function testCancelReturnsResourceUri(): void
    {
        $uri = ResourceUri::cancel('jobs', 'job-id');
        
        $this->assertInstanceOf(ResourceUri::class, $uri);
        $this->assertSame('jobs/job-id/cancel', $uri->toString());
    }

    public function testDeleteReturnsResourceUri(): void
    {
        $uri = ResourceUri::delete('models', 'model-id');
        
        $this->assertInstanceOf(ResourceUri::class, $uri);
        $this->assertSame('models/model-id', $uri->toString());
    }
}
