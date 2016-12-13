<?php
/**
 * Created by PhpStorm.
 * User: Valentin
 * Date: 09.12.2016
 * Time: 14:13
 */
namespace Spiral\LogViewer\Models;

use Spiral\LogViewer\Models\Entities\Log;
use Spiral\LogViewer\Models\Entities\Rotation;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class LogsSource
{
    /**
     * @var Finder
     */
    private $finder;

    /**
     * @var string
     */
    private $directory;

    /**
     * LogsSource constructor.
     *
     * @param Finder $finder
     */
    public function __construct(Finder $finder)
    {
        $this->finder = $finder;
        $this->directory = directory('runtime') . 'logs';
    }

    /**
     * @param string $directory
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;
    }

    /**
     * @return array
     */
    public function findLogs()
    {
        $logs = [];
        foreach ($this->finder->files()->sortByName()->in($this->directory) as $file) {
            $log = new Log($file);
            $name = $log->getName();

            if (!array_key_exists($name, $logs)) {
                $logs[$name] = $log;
            } else {
                $log = $logs[$name];
            }

            $log->addRotation($file);
        }

        return $logs;
    }

    /**
     * @return null|Log
     */
    public function findLastLog()
    {
        $files = $this->finder->files()->in($this->directory)->sort(function ($a, $b) {
            return $b->getMTime() - $a->getMTime();
        });

        foreach ($files as $file) {
            $log = new Log($file);
            $log->addRotation($file);

            return $log;
        }

        return null;
    }

    /**
     * @param string $name
     * @return null|Log
     */
    public function findLogByName($name)
    {
        $logs = $this->findLogs();
        if (!array_key_exists($name, $logs)) {
            return null;
        }

        return $logs[$name];
    }

    /**
     * @param $name
     * @return null|Rotation
     */
    public function findRotation($name)
    {
        foreach ($this->finder->files()->in($this->directory)->name($name) as $file) {
            return new Rotation($file);
        }

        return null;
    }
}