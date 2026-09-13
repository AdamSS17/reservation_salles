<?php

declare(strict_types=1);

namespace App\Validation;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

final class ReservationValidator implements ValidatorInterface
{
    public function validate(array $data): ValidationResult
    {
        $regles = [
            'salle_id'    => v::intVal()->positive(),
            'responsable' => v::stringType()->length(2, 120),
            'email'       => v::email(),
            'motif'       => v::stringType()->length(5, 255),
            'date_debut'  => v::dateTime(),
            'date_fin'    => v::dateTime(),
        ];

        $errors = [];

        foreach ($regles as $champ => $validator) {
            try {
                $validator->assert($data[$champ] ?? null);
            } catch (NestedValidationException $e) {
                $errors[$champ] = array_values($e->getMessages());
            }
        }

        // Note : la comparaison date_debut < date_fin n'est volontairement
        // PAS ici (contrainte du brief : "la comparaison entre les dates
        // sera effectuée dans la couche métier", donc dans le Service,
        // à l'Étape 8 — ce validateur ne vérifie que la syntaxe/le format).

        if (!empty($errors)) {
            return ValidationResult::failure($errors, $data);
        }

        return ValidationResult::success($data);
    }
}