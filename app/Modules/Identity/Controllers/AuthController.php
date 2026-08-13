<?php

declare(strict_types=1);

namespace MedTrack\Modules\Identity\Controllers;

use MedTrack\Core\Http\Request;
use MedTrack\Core\Http\Response;
use MedTrack\Core\Http\View;
use MedTrack\Modules\Identity\Services\AuthService;

final class AuthController
{
    public function __construct(
        private readonly AuthService $auth,
        private readonly View $view
    ) {
    }

    public function showLogin(Request $request): string
    {
        if ($this->auth->check()) {
            header('Location: /');
            exit;
        }

        return $this->view->render(
            'auth.login',
            [
                'pageTitle' => 'Connexion',
            ],
            'layouts.auth'
        );
    }

    public function login(Request $request): never
    {
        $login = trim((string) $request->input('login', ''));
        $password = (string) $request->input('password', '');

        if ($login === '' || $password === '') {
            Response::json([
                'status' => 'error',
                'message' => 'Identifiant et mot de passe obligatoires.',
            ], 422);
        }

        if (!$this->auth->attempt($login, $password)) {
            Response::json([
                'status' => 'error',
                'message' => 'Identifiants incorrects ou compte indisponible.',
            ], 401);
        }

        Response::json([
            'status' => 'success',
            'message' => 'Connexion réussie.',
            'redirect' => '/',
        ]);
    }

    public function logout(Request $request): never
    {
        $this->auth->logout();

        header('Location: /login');
        exit;
    }
}
