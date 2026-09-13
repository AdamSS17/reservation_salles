<?php

require __DIR__ . '/config/database.php';

use App\Validation\ReservationValidator;

$v = new ReservationValidator();
$r = $v->validate([
    "salle_id" => "1",
    "responsable" => "Test",
    "email" => "test@universite.sn",
    "motif" => "Motif suffisamment long pour passer",
    "date_debut" => "2026-09-20T10:00",
    "date_fin" => "2026-09-20T12:00",
]);
echo "Valide : " . ($r->isValid() ? "oui" : "non") . "\n";
print_r($r->errors());