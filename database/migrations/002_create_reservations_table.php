

<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class CreateReservationTable
{
    public function up(): void
    {
        if (Capsule::schema()->hasTable('reservation')) {
            echo "Table 'reservation' existe déjà.\n";
            return;
        }

        Capsule::schema()->create('reservation', function ($table) {
            $table->increments('id');
            $table->unsignedInteger('salle_id');
            $table->string('responsable');
            $table->string('email');
            $table->string('motif');
            $table->dateTime('date_debut');
            $table->dateTime('date_fin');
            $table->enum('statut', ['confirmée', 'annulée']);
            $table->timestamps();

            $table->foreign('salle_id')->references('id')->on('salle');
        });
    }

    public function down(): void
    {
        Capsule::schema()->dropIfExists('reservation');
    }
}


// <?php

// declare(strict_types=1);

// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Database\Capsule\Manager as Capsule;

// return [
//     'table' => 'reservation',
//     'up' => function (): void {
//         Capsule::schema()->create('reservations', function (Blueprint $table) {
//             $table->id();
//             $table->foreignId('salle_id')
//                 ->constrained('salles')
//                 ->cascadeOnDelete();
//             $table->string('responsable', 120);
//             $table->string('email', 255);
//             $table->string('motif', 255);
//             $table->dateTime('date_debut');
//             $table->dateTime('date_fin');
//             $table->enum('statut', ['confirmée', 'annulée'])
//                 ->default('confirmée');
//             $table->timestamps();
//         });
//     },
// ];