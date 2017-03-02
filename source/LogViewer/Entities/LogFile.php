<?php

namespace Spiral\LogViewer\Entities;

use Symfony\Component\Finder\SplFileInfo;

class LogFile
{
    /** @var SplFileInfo */
    private $file;

    /**
     * Log constructor.
     *
     * @param $file
     */
    public function __construct($file)
    {
        if ($file instanceof SplFileInfo) {
            $this->file = $file;
        } elseif (is_string($file)) {
            $this->file = new SplFileInfo($file, dirname($file), basename($file));
        } else {
            throw new \InvalidArgumentException(sprintf(
                'Unsupported file, string or "%s" instance is waiting.',
                SplFileInfo::class
            ));
        }
    }

    /**
     * @return string
     */
    public function pathname(): string
    {
        return $this->file->getPathname();
    }

    /**
     * @return string
     */
    public function filename(): string
    {
        return $this->file->getFilename();
    }

    /**
     * @return int
     */
    public function size(): int
    {
        return $this->file->getSize();
    }

    /**
     * @return LogTimestamp
     */
    public function timestamp(): LogTimestamp
    {
        return new LogTimestamp($this->file->getMTime(), []);
    }

    /**
     * @return string
     */
    public function content(): string
    {
        return $this->file->getContents();
    }
}