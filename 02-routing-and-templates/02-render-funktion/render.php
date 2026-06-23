<?php

function render(string $template): string
{
    ob_start();
    $dir = __DIR__ . '/templates/';

    include $dir . $template;

    return ob_get_clean();
}