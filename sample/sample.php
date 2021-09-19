<?php
use Francerz\ViewHtml\Renderer;
$view = Renderer::getView();
$view->loadLayout('my-layout', ['title'=>'Hola mundo']);
?>
<p>Este es contenido de prueba antes de las secciones.</p>
<?=$view->include('included')?>
<?=$view->section('content')?>
<p>(content) El contenido de la primer sección.</p>
<?=$view->endSection()?>
<?=$view->section('after_content')?>
<p>(after_content) Contenido que va después del contenido.</p>
<?=$view->endSection()?>
<?=$view->section('before_content')?>
<p>(before_content) Este contenido está antes que todo.</p>
<?=$view->endSection()?>
<p>Contenido de prueba después de las secciones.</p>
