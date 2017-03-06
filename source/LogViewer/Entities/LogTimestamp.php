<?php

namespace Spiral\LogViewer\Entities;

use Spiral\Models\Accessors\AbstractTimestamp;

class LogTimestamp extends AbstractTimestamp
{
    /**
     * @param mixed $value
     * @return int
     */
    public function fetchTimestamp($value): int
    {
        return $this->castTimestamp($value) ?? 0;
    }
}