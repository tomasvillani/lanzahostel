<?php

namespace Database\Factories;

use App\Models\Puesto;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PuestoFactory extends Factory
{
    protected $model = Puesto::class;

    public function definition(): array
    {
        return [
            'empresa_id' => User::factory(['tipo' => 'e']),
            'nombre' => $this->faker->jobTitle(),
            'descripcion' => $this->faker->paragraph(),
            'imagen' => null,
        ];
    }
}
