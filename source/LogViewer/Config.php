<?php

namespace Spiral\LogViewer;

use Spiral\Core\InjectableConfig;

class Config extends InjectableConfig
{
    /**
     * Configuration section.
     */
    const CONFIG = 'modules/log-viewer';

    /**
     * @var array
     */
    protected $config = [
        'directories' => []
    ];

    /**
     * @return array
     */
    public function directories(): array
    {
        return $this->config['directories'];
    }
}