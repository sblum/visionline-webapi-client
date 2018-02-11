<?php

namespace Tests\Visionline\Crm\WebApi;

use PHPUnit\Framework\TestCase;
use Visionline\Crm\WebApi\CacheEntry;

class CacheEntryTest extends TestCase
{
    const TYPE = 'type';
    const ID = 123;
    const LAST_MODIFIED = 1335939007;
    const FIELDS = ['field1', 'field2'];
    const ID_FIELDS = ['idField'];

    public function testConstruct()
    {
        $cacheEntry = new CacheEntry(
            self::TYPE,
            self::ID,
            self::LAST_MODIFIED,
            self::FIELDS,
            self::ID_FIELDS
        );

        $this->assertSame(self::TYPE, $cacheEntry->type);
        $this->assertSame(self::ID, $cacheEntry->id);
        $this->assertSame(self::LAST_MODIFIED, $cacheEntry->lastModified);
        $this->assertSame(self::FIELDS, $cacheEntry->fields);
        $this->assertSame(self::ID_FIELDS, $cacheEntry->idFields);
    }

    public function testMerge()
    {
        $now = \time();

        $firstCacheEntry = new CacheEntry(
            self::TYPE,
            self::ID,
            self::LAST_MODIFIED,
            self::FIELDS,
            self::ID_FIELDS
        );

        $secondCacheEntry = new CacheEntry(
            self::TYPE,
            self::ID,
            $now,
            ['secondField'],
            ['secondIdField']
        );

        $newCacheEntry = $firstCacheEntry->merge($secondCacheEntry);

        $this->assertSame($now, $newCacheEntry->lastModified);
        $this->assertSame(['field1', 'field2', 'secondField'], $newCacheEntry->fields);
        $this->assertSame(['idField', 'secondIdField'], $newCacheEntry->idFields);
    }

    public function testComputeKey()
    {
        $this->assertSame('type#123-de', CacheEntry::computeKey('type', 123, 'de'));
    }
}
