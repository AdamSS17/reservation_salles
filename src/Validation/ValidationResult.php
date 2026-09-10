<?php

declare(strict_types=1);

namespace App\Validation;

/**
 * Résultat d'une validation : est-ce valide, quelles erreurs, quelles
 * données ont été acceptées. Immutable une fois créé (readonly).
 */
final class ValidationResult
{
    /**
     * @param array<string, string[]> $errors   ex: ['email' => ['doit être valide']]
     * @param array<string, mixed>    $data     données acceptées (celles fournies en entrée)
     */
    private function __construct(
        private readonly bool $valid,
        private readonly array $errors,
        private readonly array $data,
    ) {
    }

    public static function success(array $data): self
    {
        return new self(true, [], $data);
    }

    /**
     * @param array<string, string[]> $errors
     */
    public static function failure(array $errors, array $data): self
    {
        return new self(false, $errors, $data);
    }

    public function isValid(): bool
    {
        return $this->valid;
    }

    /**
     * @return array<string, string[]>
     */
    public function errors(): array
    {
        return $this->errors;
    }

    public function data(): array
    {
        return $this->data;
    }
}