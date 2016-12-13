<?php
namespace Spiral\LogViewer\Controllers;

use Spiral\Core\Controller;
use Spiral\Debug\Debugger;
use Spiral\Http\Exceptions\ClientExceptions\NotFoundException;
use Spiral\Http\Input\InputManager;
use Spiral\Http\Responses\Responder;
use Spiral\LogViewer\Models\LogsSource;
use Spiral\LogViewer\Models\RemoveService;
use Spiral\Security\Traits\AuthorizesTrait;
use Spiral\Translator\Traits\TranslatorTrait;
use Spiral\Vault\Vault;
use Spiral\Views\ViewManager;

/**
 * Created by PhpStorm.
 * User: Valentin
 * Date: 09.02.2016
 * Time: 17:47
 *
 * @property InputManager $input
 * @property ViewManager  $views
 * @property Vault        $vault
 * @property Responder    $responses
 * @property Debugger     $debugger
 */
class LogViewerController extends Controller
{
    use AuthorizesTrait, TranslatorTrait;

    const GUARD_NAMESPACE = 'vault.logs';

    /**
     * @param LogsSource $source
     * @return string
     */
    public function indexAction(LogsSource $source)
    {
        return $this->views->render('log-viewer:list', [
            'source'  => $source->findLogs(),
            'lastLog' => $source->findLastLog()
        ]);
    }

    /**
     * @param string     $id
     * @param LogsSource $source
     * @return string
     */
    public function logAction($id, LogsSource $source)
    {
        $log = $source->findLogByName($id);
        if (empty($log)) {
            throw new NotFoundException;
        }

        $this->authorize('view', compact('log'));

        $rotation = null;
        if ($log->getCounter() === 1) {
            $rotation = $log->getLast();
        }

        return $this->views->render(
            'log-viewer:log',
            compact('rotation', 'log')
        );
    }

    /**
     * @param LogsSource $source
     * @return mixed
     */
    public function rotationAction(LogsSource $source)
    {
        $rotation = $source->findRotation($this->input->query('filename'));
        if (empty($rotation)) {
            throw new NotFoundException;
        }

        $this->authorize('view', compact('rotation'));

        return $this->views->render('log-viewer:rotation', compact('rotation'));
    }

    /**
     * @param RemoveService $remove
     * @return array|\Psr\Http\Message\ResponseInterface
     */
    public function removeAllAction(RemoveService $remove)
    {
        $this->authorize('remove');

        $remove->removeAll();

        $uri = $this->vault->uri('logs');
        if ($this->input->isAjax()) {
            return [
                'status'  => 200,
                'message' => $this->say('Logs deleted.'),
                'action'  => ['redirect' => $uri]
            ];
        } else {
            return $this->responses->redirect($uri);
        }
    }

    /**
     * @param string        $id
     * @param LogsSource    $source
     * @param RemoveService $remove
     * @return array|\Psr\Http\Message\ResponseInterface
     */
    public function removeLogAction($id, LogsSource $source, RemoveService $remove)
    {
        $log = $source->findLogByName($id);
        if (empty($log)) {
            throw new NotFoundException;
        }

        $this->authorize('remove', compact('log'));

        $remove->removeLog($log);

        $uri = $this->vault->uri('logs');
        if ($this->input->isAjax()) {
            return [
                'status'  => 200,
                'message' => $this->say('Log rotations deleted.'),
                'action'  => ['redirect' => $uri]
            ];
        } else {
            return $this->responses->redirect($uri);
        }
    }

    /**
     * @param LogsSource    $source
     * @param RemoveService $remove
     * @return array|\Psr\Http\Message\ResponseInterface
     */
    public function removeRotationAction(LogsSource $source, RemoveService $remove)
    {
        $rotation = $source->findRotation($this->input->query('filename'));
        if (empty($rotation)) {
            throw new NotFoundException;
        }

        $this->authorize('remove', compact('rotation'));

        $remove->removeRotation($rotation);

        $uri = $this->vault->uri('logs:log', ['id' => $rotation->getName()]);
        if ($this->input->isAjax()) {
            return [
                'status'  => 200,
                'message' => $this->say('Log rotation deleted.'),
                'action'  => ['redirect' => $uri]
            ];
        } else {
            return $this->responses->redirect($uri);
        }
    }
}