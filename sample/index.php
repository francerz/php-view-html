<?php

use Francerz\ViewHtml\Renderer;

require_once dirname(__FILE__, 2).'/vendor/autoload.php';

$renderer = new Renderer(__DIR__);
$renderer->renderOutput('sample');