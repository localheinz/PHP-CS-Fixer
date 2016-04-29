<?php

namespace PhpCsFixer\Tests;

use PhpCsFixer\FileCache;

class FileCacheTest extends \PHPUnit_Framework_TestCase
{
    public function testIsFinal()
    {
        $reflection = new \ReflectionClass('PhpCsFixer\FileCache');

        $this->assertTrue($reflection->isFinal());
    }

    public function testImplementsCacheInterface()
    {
        $reflection = new \ReflectionClass('PhpCsFixer\FileCache');

        $this->assertTrue($reflection->implementsInterface('PhpCsFixer\Cache'));
    }

    public function testConstructorSetsValues()
    {
        $version = 'foo';
        $linting = false;
        $rules = array();

        $cache = new FileCache(
            $version,
            $linting,
            $rules
        );

        $this->assertSame($version, $cache->version());
        $this->assertSame($linting, $cache->linting());
        $this->assertSame($rules, $cache->rules());
    }

    public function testDefaults()
    {
        $cache = new FileCache(
            'foo',
            false,
            array()
        );

        $this->assertSame(PHP_VERSION, $cache->php());
    }

    public function testIsStaleReturnsTrue()
    {
        
    }

    public function testGetReturnsNullIfValueHasNotBeenSet()
    {
        $cache = new FileCache(
            'foo',
            false,
            array()
        );

        $filename = 'test.php';

        $this->assertNull($cache->get($filename));
    }

    public function testCanSetAndGetValues()
    {
        $cache = new FileCache(
            'foo',
            false,
            array()
        );

        $filename = 'test.php';
        $hash = crc32('hello');

        $cache->set($filename, $hash);

        $this->assertSame($hash, $cache->get($filename));
    }

    public function testCanClearValues()
    {
        $cache = new FileCache(
            'foo',
            false,
            array()
        );

        $filename = 'test.php';
        $hash = crc32('hello');

        $cache->set($filename, $hash);
        $cache->clear($filename);

        $this->assertNull($cache->get($filename));
    }

    public function testCanSerializeAndDeserialize()
    {
        $version = 'foo';
        $linting = false;
        $rules = array();

        $cache = new FileCache(
            $version,
            $linting,
            $rules
        );

        $filename = 'test.php';
        $hash = crc32('hello');

        $cache->set($filename, $hash);

        $serialized = serialize($cache);



        $this->assertSame($hash, $cache->get($filename));

    }
}
