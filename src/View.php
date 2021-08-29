<?php

namespace Francerz\ViewHtml;

use LogicException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;

class View
{
    private $view;
    private $vars = [];

    public function __construct(string $view)
    {
        $this->view = $view;
    }

    public function set($var, $value = null)
    {
        if (is_array($var)) {
            $this->setArray($var);
        }

        if (!is_string($var)) {
            throw new LogicException('Var name must be string');
        }

        $this->vars[$var] = $value;

        return $this;
    }

    public function setArray(array $vars)
    {
        foreach ($vars as $n => $v) {
            if (is_numeric($n)) {
                throw new LogicException('All vars must have non numeric names');
            }
            $this->set($n, $v);
        }
        return $this;
    }

    public function render()
    {
        ob_start();
        extract($this->vars);
        include $this->view;
        return ob_get_clean();
    }

    public function renderPsr7(ResponseInterface $response, StreamFactoryInterface $streamFactory)
    {
        $output = $streamFactory->createStream($this->render());
        $response = $response
            ->withHeader('Content-Type', 'text/html')
            ->withBody($output);
        return $response;
    }
}