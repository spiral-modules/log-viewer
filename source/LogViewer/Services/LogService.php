<?php

namespace Spiral\LogViewer\Services;

use Spiral\Files\FileManager;
use Spiral\LogViewer\Config;
use Spiral\LogViewer\Entities\LogFile;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Vvval\Spiral\PaginableArray;

class LogService
{
    /** @var Finder */
    private $finder;

    /** @var Config */
    private $config;

    /** @var FileManager */
    private $files;

    /**
     * LogService constructor.
     *
     * @param Finder      $finder
     * @param Config      $config
     * @param FileManager $files
     */
    public function __construct(Finder $finder, Config $config, FileManager $files)
    {
        $this->finder = $finder;
        $this->files = $files;
        $this->config = $config;
    }

    /**
     * @return PaginableArray
     */
    public function getLogs(): PaginableArray
    {
        $logs = [];
        $files = $this->finder->files()->sortByName()->in($this->config->directories());

        foreach ($files as $file) {
            /** @var SplFileInfo $file */
            $logs[$file->getFilename()] = new LogFile($file);
        }

        return new PaginableArray($logs);
    }

    /**
     * @return null|LogFile
     */
    public function lastLog()
    {
        $files = $this->finder->files()->in($this->config->directories())->sort(function ($a, $b) {
            /**
             * @var SplFileInfo $a
             * @var SplFileInfo $b
             */
            return $b->getMTime() - $a->getMTime();
        });

        /** @var SplFileInfo $file */
        foreach ($files as $file) {
            return new LogFile($file);
        }

        return null;
    }

    /**
     * @param string $filename
     * @return null|LogFile
     */
    public function getLogByName(string $filename)
    {
        foreach ($this->config->directories() as $directory) {
            if (!file_exists($directory . $filename)) {
                continue;
            }

            return new LogFile($directory . $filename);
        }

        return null;
    }

    /**
     * @param LogFile $filename
     */
    public function removeLog(LogFile $filename)
    {
        foreach ($this->config->directories() as $directory) {
            if (!file_exists($directory . $filename->name())) {
                continue;
            }

            $this->files->delete($directory . $filename->name());

            return;
        }
    }

    /**
     *
     */
    public function removeAll()
    {
        /** @var SplFileInfo $file */
        foreach ($this->finder->in($this->config->directories()) as $file) {
            $this->files->delete($file->getRealPath());
        }
    }
}