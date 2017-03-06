<?php

namespace Spiral\Tests\LogViewer;

use Psr\Log\LogLevel;
use Spiral\Debug\Snapshot;
use Spiral\Debug\Traits\LoggerTrait;
use Spiral\LogViewer\Entities\LogFile;
use Spiral\LogViewer\Services\LogService;
use Spiral\Tests\BaseTest;

class LogServiceTest extends BaseTest
{
    use LoggerTrait;

    public function testGetLogs()
    {
        /** @var LogService $service */
        $service = $this->container->get(LogService::class);

        $this->assertEmpty($service->getLogs());

        $snapshot = $this->makeSnapshot('error', 123);
        $snapshot->report();

        $this->assertCount(1, $service->getLogs());

        $log = current($service->getLogs());
        $this->assertInstanceOf(LogFile::class, $log);
    }

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
    }

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

    public function testRemoveAll()
    {
        /** @var LogService $service */
        $service = $this->container->get(LogService::class);

        $this->assertEmpty($service->getLogs());

        $snapshot = $this->makeSnapshot('error', 123);
        $snapshot->report();

        $this->assertCount(1, $service->getLogs());

        $service->removeAll();

        $this->assertEmpty($service->getLogs());
    }

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

    /**
     * @param string $message
     * @param int    $code
     * @return Snapshot
     */
    protected function makeSnapshot(string $message, int $code): Snapshot
    {
        return $this->factory->make(Snapshot::class, [
            'exception' => new \Error($message, $code)
        ]);
    }
}