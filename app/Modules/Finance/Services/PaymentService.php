<?php

declare(strict_types=1);

namespace MedTrack\Modules\Finance\Services;

use InvalidArgumentException;
use MedTrack\Modules\Finance\Repositories\PaymentRepository;

final class PaymentService
{
    public function __construct(
        private readonly PaymentRepository $payments
    ) {
    }

    /**
     * Retourne tous les paiements visibles
     * depuis la supervision plateforme.
     */
    public function all(): array
    {
        return $this->payments->all();
    }

    /**
     * Recherche un paiement.
     */
    public function findById(
        int $id
    ): ?array {
        if ($id <= 0) {
            return null;
        }

        return $this->payments->findById(
            $id
        );
    }

    /**
     * Métriques globales des paiements.
     */
    public function metrics(): array
    {
        return $this->payments->metrics();
    }

    /**
     * Métriques globales des factures.
     */
    public function invoiceMetrics(): array
    {
        return $this->payments
            ->invoiceMetrics();
    }

    /**
     * Montants encaissés par devise.
     *
     * Les devises ne doivent jamais être
     * additionnées entre elles.
     */
    public function successfulAmountsByCurrency(): array
    {
        return $this->payments
            ->successfulAmountsByCurrency();
    }

    /**
     * Répartition par moyen de paiement.
     */
    public function paymentMethods(): array
    {
        return $this->payments
            ->paymentMethods();
    }

    /**
     * Répartition par fournisseur.
     *
     * Cette méthode deviendra particulièrement
     * utile avec Mobile Money.
     */
    public function providers(): array
    {
        return $this->payments
            ->providers();
    }

    /**
     * Retourne les factures récentes.
     */
    public function recentInvoices(
        int $limit = 10
    ): array {
        if ($limit <= 0) {
            throw new InvalidArgumentException(
                'La limite doit être supérieure à zéro.'
            );
        }

        return $this->payments
            ->recentInvoices(
                min(
                    $limit,
                    100
                )
            );
    }

    /**
     * Construit le tableau de bord financier
     * du Super Admin MedTrack.
     */
    public function platformOverview(): array
    {
        return [
            'metrics' =>
                $this->metrics(),

            'invoice_metrics' =>
                $this->invoiceMetrics(),

            'amounts_by_currency' =>
                $this->successfulAmountsByCurrency(),

            'payment_methods' =>
                $this->paymentMethods(),

            'providers' =>
                $this->providers(),

            'payments' =>
                $this->all(),

            'recent_invoices' =>
                $this->recentInvoices(
                    10
                ),
        ];
    }

    /**
     * Construit la fiche détaillée
     * d'un paiement.
     */
    public function platformShow(
        int $paymentId
    ): ?array {
        $this->assertId(
            $paymentId,
            'Identifiant de paiement invalide.'
        );

        $payment =
            $this->findById(
                $paymentId
            );

        if ($payment === null) {
            return null;
        }

        $invoiceId =
            (int) (
                $payment['invoice_id']
                ?? 0
            );

        if ($invoiceId <= 0) {
            throw new InvalidArgumentException(
                'Le paiement ne possède aucune facture valide.'
            );
        }

        return [
            'payment' =>
                $payment,

            'invoice_items' =>
                $this->payments
                    ->invoiceItems(
                        $invoiceId
                    ),

            'refunds' =>
                $this->payments
                    ->refunds(
                        $paymentId
                    ),
        ];
    }

    /**
     * Vérifie qu'un identifiant métier
     * est valide.
     */
    private function assertId(
        int $id,
        string $message
    ): void {
        if ($id <= 0) {
            throw new InvalidArgumentException(
                $message
            );
        }
    }
}