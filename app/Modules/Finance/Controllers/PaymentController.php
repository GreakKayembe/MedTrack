<?php

declare(strict_types=1);

namespace MedTrack\Modules\Finance\Controllers;

use MedTrack\Core\Http\Request;
use MedTrack\Core\Http\View;
use MedTrack\Modules\Finance\Services\PaymentService;
use RuntimeException;

final class PaymentController
{
    public function __construct(
        private readonly PaymentService $payments,
        private readonly View $view
    ) {
    }

    /**
     * Affiche la supervision financière globale.
     */
    public function index(
        Request $request
    ): string {
        $overview =
            $this->payments
                ->platformOverview();

        return $this->view->render(
            'finance.payments.index',
            [
                'pageTitle' =>
                    'Paiements',

                'metrics' =>
                    $overview['metrics'],

                'invoiceMetrics' =>
                    $overview['invoice_metrics'],

                'amountsByCurrency' =>
                    $overview['amounts_by_currency'],

                'paymentMethods' =>
                    $overview['payment_methods'],

                'providers' =>
                    $overview['providers'],

                'payments' =>
                    $overview['payments'],

                'recentInvoices' =>
                    $overview['recent_invoices'],
            ]
        );
    }

    /**
     * Affiche le détail d'un paiement.
     */
    public function show(
        Request $request
    ): string {
        $id =
            $this->routeId(
                $request
            );

        $data =
            $this->payments
                ->platformShow(
                    $id
                );

        if ($data === null) {
            http_response_code(
                404
            );

            return $this->view->render(
                'errors.404',
                [
                    'pageTitle' =>
                        'Paiement introuvable',
                ]
            );
        }

        return $this->view->render(
            'finance.payments.show',
            [
                'pageTitle' =>
                    'Détail du paiement',

                'payment' =>
                    $data['payment'],

                'invoiceItems' =>
                    $data['invoice_items'],

                'refunds' =>
                    $data['refunds'],
            ]
        );
    }

    /**
     * Extrait l'identifiant numérique
     * fourni par le Router.
     */
    private function routeId(
        Request $request
    ): int {
        $id =
            (int) $request->attribute(
                'id',
                0
            );

        if ($id <= 0) {
            throw new RuntimeException(
                'Identifiant de paiement invalide.'
            );
        }

        return $id;
    }
}