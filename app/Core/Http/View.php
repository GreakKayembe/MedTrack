<?php

declare(strict_types=1);

namespace MedTrack\Core\Http;

use RuntimeException;

final class View
{
    private array $sharedData = [];

    public function share(
        string $key,
        mixed $value
    ): void {
        $this->sharedData[$key] = $value;
    }


    public function __construct(
        private readonly string $viewsPath
    ) {
    }

    /**
     * Rend une variable disponible dans toutes les vues.
     */
    
    

    public function render(
        string $view,
        array $data = [],
        string $layout = 'layouts.app'
    ): string {
        $viewFile = $this->resolve($view);
        $layoutFile = $this->resolve($layout);

        /*
         * Les données spécifiques à la vue ont priorité
         * sur les données partagées.
         */
        $data = array_merge(
            $this->sharedData,
            $data
        );

        extract(
            $data,
            EXTR_SKIP
        );

        ob_start();

        try {
            require $viewFile;

            $content = (string) ob_get_clean();
        } catch (\Throwable $exception) {
            ob_end_clean();

            throw $exception;
        }

        ob_start();

        try {
            require $layoutFile;

            return (string) ob_get_clean();
        } catch (\Throwable $exception) {
            ob_end_clean();

            throw $exception;
        }
    }

    private function resolve(
        string $view
    ): string {
        $path = $this->viewsPath
            . '/'
            . str_replace('.', '/', $view)
            . '.php';

        if (!is_file($path)) {
            throw new RuntimeException(
                sprintf(
                    'Vue introuvable : %s',
                    $view
                )
            );
        }

        return $path;
    }
}