<?php

declare(strict_types=1);

namespace App\Validation;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

final class SalleValidator implements ValidatorInterface
{
    private const TYPES_AUTORISES = [
        'cours',
        'informatique',
        'laboratoire',
        'amphitheatre',
        'reunion',
    ];

    public function validate(array $data): ValidationResult
    {
        $regles = [
            'nom'      => v::stringType()->length(2, 100),
            'batiment' => v::stringType()->length(2, 100),
            'capacite' => v::intVal()->between(1, 1000),
            'type'     => v::in(self::TYPES_AUTORISES),
            'active'   => v::boolType(),
        ];

        $errors = [];

        foreach ($regles as $champ => $validator) {
            try {
                $validator->assert($data[$champ] ?? null);
            } catch (NestedValidationException $e) {
                // getMessages() retourne un tableau de messages d'erreur
                // en français par défaut (Respect\Validation gère la
                // traduction automatiquement).
                $errors[$champ] = array_values($e->getMessages());
            }
        }

        if (!empty($errors)) {
            return ValidationResult::failure($errors, $data);
        }

        return ValidationResult::success($data);
    }
}