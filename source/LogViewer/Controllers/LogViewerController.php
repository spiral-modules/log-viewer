<?php

namespace Spiral\LogViewer\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Spiral\Core\Controller;
use Spiral\Core\Traits\AuthorizesTrait;
use Spiral\Http\Exceptions\ClientExceptions\NotFoundException;
use Spiral\Http\Request\InputManager;
use Spiral\Http\Response\ResponseWrapper;
use Spiral\LogViewer\Services\LogService;
use Spiral\LogViewer\Helpers\Timestamps;
use Spiral\Translator\Traits\TranslatorTrait;
use Spiral\Vault\Vault;
use Spiral\Views\ViewManager;

/**
 *
 * @property InputManager    $input
 * @property ViewManager     $views
 * @property Vault           $vault
 * @property ResponseWrapper $response
 */
class LogViewerController extends Controller
{
    use AuthorizesTrait, TranslatorTrait;

    const GUARD_NAMESPACE = 'vault.logs';

    /**
     * @param LogService $source
     * @param Timestamps $timestamps
     * @return string
     */
    public function indexAction(LogService $source, Timestamps $timestamps)
    {
        return $this->views->render('log-viewer:list', [
            'selector'   => $source->getLogs(),
            'lastLog'    => $source->lastLog(),
            'timestamps' => $timestamps
        ]);
    }

    /**
     * @param LogService $source
     * @param Timestamps $timestamps
     * @return string
     */
    public function viewAction(LogService $source, Timestamps $timestamps)
    {
        $filename = $this->input->input('filename');
        $log = $source->getLogByName($filename);

        if (empty($log)) {
            throw new NotFoundException;
        }

        $this->authorize('view', compact('log'));

        return $this->views->render('log-viewer:log', compact('log', 'timestamps'));
    }

    /**
     * @param LogService $source
     * @return array|\Psr\Http\Message\ResponseInterface
     */
    public function removeAllAction(LogService $source)
    {
        $this->authorize('remove');

        $source->removeAll();

        $uri = $this->vault->uri('logs');
        if ($this->input->isAjax()) {
            return [
                'status'  => 200,
                'message' => $this->say('Logs deleted.'),
                'action'  => ['redirect' => $uri]
            ];
        } else {
            return $this->response->redirect($uri);
        }
    }

    /**
     * @param LogService             $source
     * @param ServerRequestInterface $request
     * @return array|\Psr\Http\Message\ResponseInterface
     */
    public function removeAction(LogService $source, ServerRequestInterface $request)
    {
        $filename = $this->input->input('filename');
        $log = $source->getLogByName($filename);

        if (empty($log)) {
            throw new NotFoundException;
        }

        $this->authorize('remove', compact('log'));

        $source->removeLog($log);

        $uri = $this->removeBackURI($request);

        if ($this->input->isAjax()) {
            return [
                'status'  => 200,
                'message' => $this->say('Log rotations deleted.'),
                'action'  => ['redirect' => $uri]
            ];
        } else {
            return $this->response->redirect($uri);
        }
    }

    /**
     * Build redirect URI for removal operation.
     *
     * @param ServerRequestInterface $request
     * @return \Psr\Http\Message\UriInterface
     */
    protected function removeBackURI(ServerRequestInterface $request)
    {
        $query = $request->getQueryParams();
        if (array_key_exists('backToList', $query)) {
            $uri = $this->vault->uri('logs');
        } else {
            $uri = $request->getServerParams()['HTTP_REFERER'];
        }

        return $uri;
    }
}