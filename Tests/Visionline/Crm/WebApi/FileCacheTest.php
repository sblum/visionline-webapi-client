<?php

namespace Tests\Visionline\Crm\WebApi;

use PHPUnit\Framework\TestCase;
use Visionline\Crm\WebApi\Cache;
use Visionline\Crm\WebApi\FileCache;

class FileCacheTest extends TestCase
{
    const DATA = ['my' => 'data'];
    const KEY = 'myKey';

    public function testConstruct()
    {
        $tempDir = \sys_get_temp_dir();
        $fileCache = new FileCache($tempDir);

        $this->assertAttributeSame($tempDir, 'directory', $fileCache);
        $this->assertTrue($fileCache instanceof Cache);
    }

    public function testPut()
    {
        $tempDir = \sys_get_temp_dir();
        $fileCache = new FileCache($tempDir);

        $fileCache->put(self::KEY, self::DATA);
        $this->assertFileExists($this->getCacheFilename($tempDir, self::KEY));
    }

    /**
     * @depends testPut
     */
    public function testGet()
    {
        $tempDir = \sys_get_temp_dir();
        $fileCache = new FileCache($tempDir);

        $this->assertSame(self::DATA, $fileCache->get(self::KEY));
    }

    /**
     * @depends testGet
     */
    public function testClear()
    {
        $tempDir = \sys_get_temp_dir();
        $fileCache = new FileCache($tempDir);

        $this->assertFileExists($this->getCacheFilename($tempDir, self::KEY));
        $fileCache->clear();

        $this->assertFileNotExists($this->getCacheFilename($tempDir, self::KEY));
        $this->assertNull($fileCache->get(self::KEY));
    }

    private function getCacheFilename(string $tempDir, string $key): string
    {
        return \sprintf('%s%s%s', $tempDir, DIRECTORY_SEPARATOR, \sha1($key));
    }
}
