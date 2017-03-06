<?php

namespace Spiral\Tests\LogViewer;

use Psr\Log\LogLevel;
use Spiral\Debug\Traits\LoggerTrait;
use Spiral\LogViewer\Entities\LogFile;
use Spiral\LogViewer\Services\LogService;
use Spiral\Tests\HttpTest;

class LogServiceTest extends HttpTest
{
    use LoggerTrait;

    /**
     * Can get all logs.
     */
    public function testGetLogs()
    {
        /** @var LogService $service */
        $service = $this->container->get(LogService::class);

        $this->assertEmpty($service->getLogs());

        $snapshot = $this->makeSnapshot('error', 123);
        $snapshot->report();

        $this->assertCount(1, $service->getLogs());

        $this->get('/controller/action');
        $this->assertCount(2, $service->getLogs());

        $log = current($service->getLogs());
        $this->assertInstanceOf(LogFile::class, $log);
    }

    /**
     * Take really last log.
     */
    public function testLastLog()
    {
        /** @var LogService $service */
        $service = $this->container->get(LogService::class);

        $this->assertEmpty($service->lastLog());

        $snapshot = $this->makeSnapshot('error', 123);
        $snapshot->report();

        /** @var LogFile $last */
        $last = $service->lastLog();

        $this->assertNotEmpty($last);
        $this->assertInstanceOf(LogFile::class, $last);

        $this->get('/controller/action');

        /** @var LogFile $last */
        $last2 = $service->lastLog();
        $this->assertNotEmpty($last2);

        $this->assertNotEquals($last->name(), $last2->name());
    }

    /**
     * Get log by name is really what we want
     */
    public function testGetLogByName()
    {
        /** @var LogService $service */
        $service = $this->container->get(LogService::class);

        $snapshot = $this->makeSnapshot('error', 123);
        $snapshot->report();

        $this->assertEmpty($service->getLogByName('some-name'));

        /** @var LogFile $last */
        $last = $service->lastLog();
        $this->assertNotEmpty($last);

        $log = $service->getLogByName($last->name());

        $this->assertNotEmpty($log);
        $this->assertInstanceOf(LogFile::class, $log);

        $this->assertEquals($last->name(), $log->name());
    }

    /**
     * Remove all logs
     */
    public function testRemoveAll()
    {
        /** @var LogService $service */
        $service = $this->container->get(LogService::class);

        $this->assertEmpty($service->getLogs());

        $snapshot = $this->makeSnapshot('error', 123);
        $snapshot->report();

        $this->assertCount(1, $service->getLogs());

        $this->get('/controller/action');
        $this->assertCount(2, $service->getLogs());

        $service->removeAll();

        $this->assertEmpty($service->getLogs());
    }

    /**
     * Remove
     */
    public function testRemove()
    {
        /** @var LogService $service */
        $service = $this->container->get(LogService::class);

        $this->assertEmpty($service->getLogs());

        $snapshot = $this->makeSnapshot('error', 123);
        $snapshot->report();

        $this->assertCount(1, $service->getLogs());

        /** @var LogFile $last */
        $last = $service->lastLog();
        $this->assertNotEmpty($last);

        $service->removeLog(new LogFile('some-name'));

        $this->assertCount(1, $service->getLogs());

        $service->removeLog($last);

        $this->assertEmpty($service->getLogs());
    }
}