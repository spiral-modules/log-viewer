<?php

namespace Spiral\Tests\LogViewer;

use Spiral\LogViewer\Entities\LogFile;
use Spiral\LogViewer\Entities\LogTimestamp;
use Spiral\Tests\BaseTest;
use Symfony\Component\Finder\SplFileInfo;

class LogFileTest extends BaseTest
{
    public function testEntity()
    {
        $filename = __FILE__;
        $entity = new LogFile($filename);

        $this->assertEquals(basename($filename), $entity->name());
        $this->assertEquals($filename, $entity->filename());
        $this->assertNotEmpty($entity->size());
        $this->assertNotEmpty($entity->content());

        $this->assertInstanceOf(LogTimestamp::class, $entity->timestamp());
    }

    public function testConstructor()
    {
        $filename = __FILE__;

        $spl = new SplFileInfo($filename, dirname($filename), basename($filename));
        $entity = new LogFile($spl);
        $entity2 = new LogFile($filename);

        $this->assertSame($entity->filename(), $entity2->filename());

        $this->expectException(\InvalidArgumentException::class);
        $entity3 = new LogFile([]);
        $entity4 = new LogFile(new \SplFileInfo($filename));
    }
}