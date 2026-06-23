<?php

function render(
    string $template,
    array $data = []
): string {

    extract($data);

    ob_start();

    include __DIR__ . "/templates/" . $template;

    $content = ob_get_clean();

    ob_start();

    include __DIR__ . "/templates/layout.php";

    return ob_get_clean();
}