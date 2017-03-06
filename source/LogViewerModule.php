<?php

namespace Spiral;

use Spiral\Core\DirectoriesInterface;
use Spiral\LogViewer\Config;
use Spiral\Modules\ModuleInterface;
use Spiral\Modules\PublisherInterface;
use Spiral\Modules\RegistratorInterface;

/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Valentin V (vvval)
 */
class LogViewerModule implements ModuleInterface
{
    /**
     * @param RegistratorInterface $registrator
     */
    public function register(RegistratorInterface $registrator)
    {
        //Register view namespace
        $registrator->configure('views', 'namespaces', 'spiral/log-viewer', [
            "'log-viewer' => [",
            "   directory('libraries') . 'spiral/log-viewer/source/views/',",
            "   /*{{namespaces.log-viewer}}*/",
            "],"
        ]);

        //Register controller in navigation config
        $registrator->configure('modules/vault', 'controllers', 'spiral/log-viewer', [
            "'logs' => \\Spiral\\LogViewer\\Controllers\\LogViewerController::class,",
        ]);

        //Register menu item in navigation config
        $registrator->configure('modules/vault', 'navigation.vault', 'spiral/log-viewer', [
            "'logs' => [",
            "    'title'    => 'Logs',",
            "    'requires' => 'vault.logs'",
            "],",
            "/*{{navigation.vault.logs}}*/",
        ]);
    }

    /**
     * @param PublisherInterface   $publisher
     * @param DirectoriesInterface $directories
     */
    public function publish(PublisherInterface $publisher, DirectoriesInterface $directories)
    {
        $publisher->publish(
            __DIR__ . '/config/viewer.php',
            $directories->directory('config') . Config::CONFIG . '.php',
            PublisherInterface::FOLLOW
        );
    }
}