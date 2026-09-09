

<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class CreateSalleTable
{
    public function up(): void
    {
        if (Capsule::schema()->hasTable('salle')) {
            echo "Table 'salle' existe déjà.\n";
            return;
        }

        Capsule::schema()->create('salle', function ($table) {
            $table->increments('id');
            $table->string('nom');
            $table->string('batiment');
            $table->integer('capacite');
            $table->enum('type', ['cours', 'informatique', 'laboratoire', 'amphitheatre', 'reunion']);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Capsule::schema()->dropIfExists('salle');
    }
}

// <?php

// declare(strict_types=1);

// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Database\Capsule\Manager as Capsule;

// return [
//     'table' => 'salle',
//     'up' => function (): void {
//         Capsule::schema()->create('salles', function (Blueprint $table) {
//             $table->id();
//             $table->string('nom', 100);
//             $table->string('batiment', 100);
//             $table->unsignedInteger('capacite');
//             $table->enum('type', [
//                 'cours',
//                 'informatique',
//                 'laboratoire',
//                 'amphitheatre',
//                 'reunion',
//             ]);
//             $table->boolean('active')->default(true);
//             $table->timestamps();
//         });
//     },
// ];