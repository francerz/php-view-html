<?php

namespace Francerz\ViewHtml;

use Francerz\PowerData\Strings;
use LogicException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;

class Renderer
{
    private static $view = null;
    private static $layout = null;

    public static function getView() : ?View
    {
        return static::$view;
    }
    public static function getLayout() : ?Layout
    {
        return static::$layout;
    }

    private $viewsPath;
    private $streamFactory;

    public function __construct(string $viewsPath = '', ?StreamFactoryInterface $streamFactory = null)
    {
        $this->setViewsPath($viewsPath);
        if (isset($streamFactory)) {
            $this->setStreamFactory($streamFactory);
        }
    }

    public function setViewsPath(string $viewsPath)
    {
        $this->viewsPath = $viewsPath;
    }

    public function setStreamFactory(StreamFactoryInterface $streamFactory)
    {
        $this->streamFactory = $streamFactory;
    }

    public function getViewsPath()
    {
        return $this->viewsPath;
    }

    public function getViewPath(string $view)
    {
        $view = Strings::endsWith($view, '.php') ? $view : $view.'.php';
        $view = ltrim($view, '/');
        $viewPath = "{$this->viewsPath}/{$view}";
        return strtr($viewPath, '/', DIRECTORY_SEPARATOR);
    }

    public function setLayout(Layout $layout)
    {
        static::$layout = $layout;
    }

    public function render(string $view, array $data = [])
    {
        $viewPath = $this->getViewPath($view);

        static::$view = $view = new View($this);
        $output = (function($viewPath, $data) {
            ob_start();
            extract($data);
            include $viewPath;
            return ob_get_clean();
        })($viewPath, $data);
        static::$view = null;
        if (isset(static::$layout)) {
            $output.= static::$layout->render();
        }
        // Returns rendering hidding current context to View file.


        return $output;
    }

    public function renderOutput(string $view, array $data = [])
    {
        echo $this->render($view, $data);
    }

    public function renderPsr7(ResponseInterface $response, string $view, array $data = [])
    {
        if (!isset($this->streamFactory)) {
            throw new LogicException("Cannot render due missing StreamFactory.");
        }
        $body = $this->streamFactory->createStream($this->render($view, $data));
        return $response
            ->withHeader('Content-Type','text/html')
            ->withBody($body);
    }
}