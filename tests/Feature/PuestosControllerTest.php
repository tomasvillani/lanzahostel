<?php

namespace Tests\Feature;

use App\Models\Puesto;
use App\Models\Solicitud;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PuestosControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function solo_empresas_pueden_ver_sus_puestos_en_index()
    {
        $empresa = User::factory()->create(['tipo' => 'e']);
        $cliente = User::factory()->create(['tipo' => 'c']);

        Puesto::factory()->count(3)->create(['empresa_id' => $empresa->id]);

        $response = $this->actingAs($empresa)->get(route('myjobs.index'));
        $response->assertStatus(200);
        $response->assertViewHas('puestos');

        $response = $this->actingAs($cliente)->get(route('myjobs.index'));
        $response->assertStatus(403);
    }

    #[Test]
    public function solo_empresas_pueden_acceder_a_create()
    {
        $empresa = User::factory()->create(['tipo' => 'e']);
        $cliente = User::factory()->create(['tipo' => 'c']);

        $response = $this->actingAs($empresa)->get(route('myjobs.create'));
        $response->assertStatus(200);

        $response = $this->actingAs($cliente)->get(route('myjobs.create'));
        $response->assertStatus(403);
    }

    #[Test]
    public function puede_crear_puesto_con_imagen()
    {
        Storage::fake('public');

        $empresa = User::factory()->create(['tipo' => 'e']);

        $file = UploadedFile::fake()->image('puesto.jpg');

        $response = $this->actingAs($empresa)->post(route('myjobs.store'), [
            'nombre' => 'Desarrollador PHP',
            'descripcion' => 'Trabajo remoto',
            'imagen' => $file,
        ]);

        $response->assertRedirect(route('myjobs.index'));
        $this->assertDatabaseHas('puestos', [
            'nombre' => 'Desarrollador PHP',
            'empresa_id' => $empresa->id,
        ]);

        Storage::disk('public')->assertExists(Puesto::first()->imagen);
    }

    #[Test]
    public function show_muestra_puesto_y_verifica_solicitud()
    {
        $empresa = User::factory()->create(['tipo' => 'e']);
        $cliente = User::factory()->create(['tipo' => 'c']);

        $puesto = Puesto::factory()->create(['empresa_id' => $empresa->id]);

        $response = $this->actingAs($cliente)->get(route('jobs.show', $puesto));
        $response->assertStatus(200);
        $response->assertViewHas('yaSolicito', false);

        Solicitud::factory()->create([
            'cliente_id' => $cliente->id,
            'puesto_id' => $puesto->id,
        ]);

        $response = $this->actingAs($cliente)->get(route('jobs.show', $puesto));
        $response->assertViewHas('yaSolicito', true);
    }

    #[Test]
    public function solo_empresa_dueña_puede_editar_puesto()
    {
        $empresa1 = User::factory()->create(['tipo' => 'e']);
        $empresa2 = User::factory()->create(['tipo' => 'e']);

        $puesto = Puesto::factory()->create(['empresa_id' => $empresa1->id]);

        $response = $this->actingAs($empresa1)->get(route('myjobs.edit', $puesto));
        $response->assertStatus(200);

        $response = $this->actingAs($empresa2)->get(route('myjobs.edit', $puesto));
        $response->assertStatus(403);
    }

    #[Test]
    public function puede_actualizar_puesto_y_eliminar_o_cambiar_imagen()
    {
        Storage::fake('public');

        $empresa = User::factory()->create(['tipo' => 'e']);
        $puesto = Puesto::factory()->create(['empresa_id' => $empresa->id, 'imagen' => null]);

        $file = UploadedFile::fake()->image('nuevo.jpg');

        $response = $this->actingAs($empresa)->put(route('myjobs.update', $puesto), [
            'nombre' => 'Nuevo nombre',
            'descripcion' => 'Nueva descripcion',
            'imagen' => $file,
            'eliminar_imagen' => false,
        ]);

        $response->assertRedirect(route('myjobs.index'));
        $puesto->refresh();
        $this->assertEquals('Nuevo nombre', $puesto->nombre);
        Storage::disk('public')->assertExists($puesto->imagen);

        $response = $this->actingAs($empresa)->put(route('myjobs.update', $puesto), [
            'nombre' => 'Nuevo nombre',
            'descripcion' => 'Nueva descripcion',
            'eliminar_imagen' => true,
        ]);

        $response->assertRedirect(route('myjobs.index'));
        $puesto->refresh();
        $this->assertNull($puesto->imagen);
    }

    #[Test]
    public function solo_empresa_dueña_puede_eliminar_puesto()
    {
        Storage::fake('public');

        $empresa1 = User::factory()->create(['tipo' => 'e']);
        $empresa2 = User::factory()->create(['tipo' => 'e']);

        $puesto = Puesto::factory()->create(['empresa_id' => $empresa1->id]);
        $puesto->imagen = UploadedFile::fake()->image('img.jpg')->store('puestos', 'public');
        $puesto->save();

        $response = $this->actingAs($empresa2)->delete(route('myjobs.destroy', $puesto));
        $response->assertStatus(403);

        $response = $this->actingAs($empresa1)->delete(route('myjobs.destroy', $puesto));
        $response->assertRedirect(route('myjobs.index'));
        $this->assertDatabaseMissing('puestos', ['id' => $puesto->id]);
        Storage::disk('public')->assertMissing($puesto->imagen);
    }

    #[Test]
    public function all_puede_listar_puestos_y_filtrar_por_busqueda()
    {
        $empresa = User::factory()->create(['tipo' => 'e']);
        $puesto1 = Puesto::factory()->create(['nombre' => 'Desarrollador PHP', 'empresa_id' => $empresa->id]);
        $puesto2 = Puesto::factory()->create(['nombre' => 'Diseñador UX', 'empresa_id' => $empresa->id]);

        $response = $this->get(route('jobs.index'));
        $response->assertStatus(200);
        $response->assertViewHas('puestos');

        $response = $this->get(route('jobs.index', ['q' => 'PHP']));
        $response->assertStatus(200);
        $response->assertViewHas('puestos', function ($puestos) use ($puesto1) {
            return $puestos->contains($puesto1);
        });

        $empresa->name = 'Empresa XYZ';
        $empresa->save();

        $response = $this->get(route('jobs.index', ['q' => 'XYZ']));
        $response->assertStatus(200);
        $response->assertViewHas('puestos', function ($puestos) use ($puesto1) {
            return $puestos->contains($puesto1);
        });
    }
}
