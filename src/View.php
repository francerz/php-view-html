<?php

namespace Francerz\ViewHtml;

class View
{
    private $renderer;
    private $section = null;
    /**
     * @var string[]
     */
    private $sections = [];

    public function __construct(Renderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public function getRenderer()
    {
        return $this->renderer;
    }

    public function loadLayout(string $layout, array $data = [])
    {
        $layout = new Layout($this, $layout, $data);
    }

    public function findSection(string $name)
    {
        return $this->sections[$name] ?? null;
    }

    public function section(string $name)
    {
        $self = $this;
        ob_start(function(string $buffer) use ($self, $name) {
            $self->sections[$name] = $buffer;
            return $buffer;
        });
    }

    public function endSection()
    {
        ob_get_clean();
    }

    public function include(string $view, array $data = [])
    {
        $viewPath = $this->renderer->getViewPath($view);
        return (function(string $viewPath, array $data) {
            extract($data);
            include $viewPath;
        })($viewPath, $data);
    }
}