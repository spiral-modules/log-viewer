<?php

namespace Spiral\LogViewer\Models\Entities;

use Carbon\Carbon;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Created by PhpStorm.
 * User: Valentin
 * Date: 09.12.2016
 * Time: 19:17
 */
class Log
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Rotation[]
     */
    private $rotations = [];

    /**
     * @var Rotation|null
     */
    private $last;

    /**
     * @var Rotation|null
     */
    private $first;

    /**
     * @var int
     */
    private $size;

    /**
     * Log constructor.
     *
     * @param SplFileInfo $file
     */
    public function __construct(SplFileInfo $file)
    {
        $this->name = $this->fetchName($file);
    }

    /**
     * @param SplFileInfo $file
     */
    public function addRotation(SplFileInfo $file)
    {
        $rotation = new Rotation($file);
        $this->rotations[$file->getFilename()] = $rotation;

        if (empty($this->last) || $this->last->when() < $rotation->when()) {
            $this->last = clone $rotation;
        }

        if (empty($this->first) || $this->first->when() > $rotation->when()) {
            $this->first = clone $rotation;
        }

        $this->size += $rotation->getSize();
    }

    /**
     * @param SplFileInfo $file
     * @return string
     */
    private function fetchName(SplFileInfo $file)
    {
        $filename = $file->getFilename();
        $extension = $file->getExtension();
        $name = substr($filename, 0, -1 * (strlen($extension) + 1));

        if (preg_match('/^(.+)-[\d]{4}-[\d]{2}-[\d]{2}/is', $name, $match)) {
            return $match[1];
        }

        return $file->getFilename();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param bool $relative
     * @return Carbon|string
     */
    public function whenLast($relative = false)
    {
        if (empty($this->last)) {
            return '&mdash;';
        }

        return $this->last->when($relative);
    }

    /**
     * @return null|Rotation
     */
    public function getLast()
    {
        return $this->last;
    }

    /**
     * @return null|Rotation
     */
    public function getFirst()
    {
        return $this->first;
    }

    /**
     * @return int
     */
    public function getCounter()
    {
        return count($this->rotations);
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @return Rotation[]
     */
    public function getRotations()
    {
        $rotations = $this->rotations;
        usort($rotations, function ($a, $b) {
            return $b->getFile()->getMTime()-$a->getFile()->getMTime();
        });

        return $rotations;
    }
}