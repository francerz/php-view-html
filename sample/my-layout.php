<?php
use Francerz\ViewHtml\Renderer;
$layout = Renderer::getLayout();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$title?></title>
</head>
<body>
    <?=$layout->section('before_content')?>
    <?=$layout->section('content')?>
    <?=$layout->section('after_content')?>
    <?=$layout->section('undefined_content')?>
</body>
</html>