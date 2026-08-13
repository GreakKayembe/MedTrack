<?php

declare(strict_types=1);

namespace MedTrack\Core\Http;

use RuntimeException;

final class View
{
    public function __construct(
        private readonly string $viewsPath
    ) {
    }

    public function render(
        string $view,
        array $data = [],
        string $layout = 'layouts.app'
    ): string {
        $viewFile = $this->resolve($view);
        $layoutFile = $this->resolve($layout);

        extract($data, EXTR_SKIP);

        ob_start();
        require $viewFile;
        $content = (string) ob_get_clean();

        ob_start();
        require $layoutFile;

        return (string) ob_get_clean();
    }

    private function resolve(string $view): string
    {
        $path = $this->viewsPath
            . '/'
            . str_replace('.', '/', $view)
            . '.php';

        if (!is_file($path)) {
            throw new RuntimeException(
                sprintf('Vue introuvable : %s', $view)
            );
        }

        return $path;
    }
}