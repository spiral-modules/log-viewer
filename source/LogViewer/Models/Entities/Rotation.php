<?php

namespace Spiral\LogViewer\Models\Entities;

use Carbon\Carbon;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Class Rotation.
 * Represents single log rotation file.
 * Is a \Symfony\Component\Finder\SplFileInfo object wrapper
 *
 * @package Spiral\LogViewer\Models\Entities
 */
class Rotation
{
    /**
     * @var SplFileInfo
     */
    private $file;

    /**
     * @var string
     */
    protected $nameRegexp = '/^(.+)-[\d]{4}-[\d]{2}-[\d]{2}/is';

    /**
     * Rotation constructor.
     *
     * @param SplFileInfo $file
     */
    public function __construct(SplFileInfo $file)
    {
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function getName()
    {
        $filename = $this->file->getFilename();
        $extension = $this->file->getExtension();
        $name = substr($filename, 0, -1 * (strlen($extension) + 1));

        if (preg_match($this->nameRegexp, $name, $match)) {
            return $match[1];
        }

        return $this->file->getFilename();
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->file->getFilename();
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->file->getContents();
    }

    /**
     * @return SplFileInfo
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param bool $relative
     * @return Carbon|string
     */
    public function when($relative = false)
    {
        $date = new Carbon();
        $date->setTimestamp($this->file->getMTime());
        if (!empty($relative)) {
            return $date->diffForHumans(Carbon::now());
        }

        return $date;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->file->getSize();
    }
}