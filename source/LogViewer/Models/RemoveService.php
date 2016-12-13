<?php
/**
 * Created by PhpStorm.
 * User: Valentin
 * Date: 13.12.2016
 * Time: 19:24
 */

namespace Spiral\LogViewer\Models;


use Spiral\LogViewer\Models\Entities\Log;
use Spiral\LogViewer\Models\Entities\Rotation;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class RemoveService
{
    /**
     * @var Filesystem
     */
    protected $fileSystem;

    /**
     * @var Finder
     */
    protected $finder;

    /**
     * @var string
     */
    protected $directory;

    /**
     * RemoveService constructor.
     *
     * @param Filesystem $filesystem
     * @param Finder     $finder
     */
    public function __construct(Filesystem $filesystem, Finder $finder)
    {
        $this->fileSystem = $filesystem;
        $this->finder = $finder;
        $this->directory = directory('runtime') . 'logs';
    }

    /**
     * Set directory.
     *
     * @param string $directory
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;
    }

    /**
     * Remove log rotation.
     *
     * @param Rotation $rotation
     */
    public function removeRotation(Rotation $rotation)
    {
        $this->fileSystem->remove($rotation->getFile()->getPathname());
    }

    /**
     * Remove log with all rotations.
     *
     * @param Log $log
     */
    public function removeLog(Log $log)
    {
        foreach ($log->getRotations() as $rotation) {
            $this->removeRotation($rotation);
        }
    }

    /**
     * Remove all logs
     */
    public function removeAll()
    {
        foreach ($this->finder->files()->in($this->directory) as $file) {
            $this->fileSystem->remove($file->getPathname());
        }
    }
}