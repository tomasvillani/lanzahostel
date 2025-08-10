<?php

namespace Database\Factories;

use App\Models\Solicitud;
use App\Models\Puesto;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SolicitudFactory extends Factory
{
    protected $model = Solicitud::class;

    public function definition(): array
    {
        return [
            'cliente_id' => User::factory()->state(['tipo' => 'c']),
            'puesto_id' => Puesto::factory(),
            'fecha_publicacion' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'estado' => $this->faker->randomElement(['p', 'a', 'r']),
        ];
    }
}
