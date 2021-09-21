<?php

namespace Francerz\ViewHtml;

class Layout
{
    private $renderer;
    private $view;
    private $layoutPath;
    private $data;

    public function __construct(View $view, string $layout, array $data)
    {
        $this->view = $view;
        $this->renderer = $view->getRenderer();
        $this->renderer->setLayout($this);

        $this->layoutPath = $this->renderer->getViewPath($layout);
        $this->data = $data;
    }

    public function section(string $name)
    {
        $section = $this->view->findSection($name);
        if (is_null($section)) {
            return;
        }
        echo $section;
    }

    public function render()
    {
        return (function(string $layoutPath, array $data) {
            ob_start();
            extract($data);
            require $layoutPath;
            return ob_get_clean();
        })($this->layoutPath, $this->data);
    }

    public function insert(string $view, array $data = [])
    {
        $viewPath = $this->renderer->getViewPath($view);
        return (function(string $viewPath, array $data) {
            extract($data);
            include $viewPath;
        })($viewPath, $data);
    }
}