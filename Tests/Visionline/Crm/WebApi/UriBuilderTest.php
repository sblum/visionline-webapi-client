<?php

namespace Tests\Visionline\Crm\WebApi;

use PHPUnit\Framework\TestCase;
use Visionline\Crm\WebApi\UriBuilder;

class UriBuilderTest extends TestCase
{
    const COMPLETE_PATH = 'https://user:pw@app2.visionline.at:443/myPath/file.php?q=querystring#fragment';
    const FULL_PATH = 'https://app2.visionline.at/my/path';
    const RELATIVE_PATH = '/my/path';

    public function testConstruct()
    {
        $uriBuilder = new UriBuilder(self::COMPLETE_PATH);

        $this->assertAttributeSame('https', 'scheme', $uriBuilder);
        $this->assertAttributeSame('app2.visionline.at', 'host', $uriBuilder);
        $this->assertAttributeSame(443, 'port', $uriBuilder);
        $this->assertAttributeSame('user', 'user', $uriBuilder);
        $this->assertAttributeSame('pw', 'pass', $uriBuilder);
        $this->assertAttributeSame('/myPath/file.php', 'path', $uriBuilder);
        $this->assertAttributeSame('q=querystring', 'query', $uriBuilder);
        $this->assertAttributeSame('fragment', 'fragment', $uriBuilder);
    }

    public function testIsFullQualified()
    {
        $relative = new UriBuilder(self::RELATIVE_PATH);
        $this->assertFalse($relative->isFullQualified());

        $full = new UriBuilder(self::FULL_PATH);
        $this->assertTrue($full->isFullQualified());
    }

    public function testAddParameter()
    {
        $full = new UriBuilder(self::FULL_PATH);
        $full->addParameter('key', 'value');

        $this->assertAttributeSame('key=value', 'query', $full);

        $complete = new UriBuilder(self::COMPLETE_PATH);
        $complete->addParameter('key', 'value');

        $this->assertAttributeSame('q=querystring&key=value', 'query', $complete);
    }

    public function testAddParameters()
    {
        $full = new UriBuilder(self::FULL_PATH);
        $full->addParameters([
            'key' => 'value',
            'foo' => 'bar',
        ]);

        $this->assertAttributeSame('key=value&foo=bar', 'query', $full);
    }

    public function testToString()
    {
        $complete = new UriBuilder(self::COMPLETE_PATH);
        $this->assertSame(self::COMPLETE_PATH, $complete->__toString());

        $full = new UriBuilder(self::FULL_PATH);
        $this->assertSame(self::FULL_PATH, $full->__toString());

        $relative = new UriBuilder(self::RELATIVE_PATH);
        $this->assertSame(self::RELATIVE_PATH, $relative->__toString());
    }

    public function testSetScheme()
    {
        $uriBuilder = new UriBuilder('');
        $uriBuilder->setScheme('https');
        $this->assertSame('https', $uriBuilder->getScheme());
    }

    public function testSetHost()
    {
        $uriBuilder = new UriBuilder('');
        $uriBuilder->setHost('app2.visionline.at');
        $this->assertSame('app2.visionline.at', $uriBuilder->getHost());
    }

    public function testSetPath()
    {
        $uriBuilder = new UriBuilder('');
        $uriBuilder->setPath(self::RELATIVE_PATH);
        $this->assertSame(self::RELATIVE_PATH, $uriBuilder->getPath());
    }
}
