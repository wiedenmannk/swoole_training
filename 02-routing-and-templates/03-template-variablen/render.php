<?php

function render(string $template, array $data = []): string
{
    extract($data);

    ob_start();

    include __DIR__ . '/templates/' . $template;

    return ob_get_clean();
}