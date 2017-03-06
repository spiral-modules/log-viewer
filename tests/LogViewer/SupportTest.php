<?php

namespace Spiral\Tests\LogViewer;

use Spiral\LogViewer\Config;
use Spiral\LogViewer\Entities\LogTimestamp;
use Spiral\LogViewer\Helpers\Timestamps;
use Spiral\Tests\BaseTest;

class SupportTest extends BaseTest
{
    /**
     * Test config class.
     */
    public function testConfig()
    {
        /** @var Config $config */
        $config = $this->container->get(Config::class);

        $this->assertNotEmpty($config->directories());
        $this->assertTrue(is_array($config->directories()));
    }

    /**
     * Test timestamps helper.
     */
    public function testTimestamps()
    {
        /** @var Timestamps $names */
        $names = $this->container->get(Timestamps::class);

        $timestamp = new LogTimestamp(new \DateTime(), []);

        $this->assertEquals($timestamp, $names->getTime($timestamp));
        $this->assertNotEquals('filename', $names->getTime($timestamp, true));
    }
}