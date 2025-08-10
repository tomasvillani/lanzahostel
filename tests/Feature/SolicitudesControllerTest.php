<?php

namespace Tests\Feature;

use App\Models\Puesto;
use App\Models\Solicitud;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SolicitudesControllerTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function cliente_puede_enviar_solicitud_a_un_puesto()
    {
        $cliente = User::factory()->create(['tipo' => 'c', 'cv' => 'cv.pdf']);
        $puesto = Puesto::factory()->create();

        $response = $this->actingAs($cliente)
            ->post(route('applications.store', $puesto));

        $response->assertRedirect(route('applications.sent'));
        $this->assertDatabaseHas('solicitudes', [
            'cliente_id' => $cliente->id,
            'puesto_id' => $puesto->id,
            'estado' => 'p',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function no_puede_postularse_sin_cv()
    {
        $cliente = User::factory()->create(['tipo' => 'c', 'cv' => null]);
        $puesto = Puesto::factory()->create();

        $response = $this->actingAs($cliente)
            ->post(route('applications.store', $puesto));

        $response->assertSessionHasErrors([0]);
        $this->assertFalse(Solicitud::where('cliente_id', $cliente->id)->where('puesto_id', $puesto->id)->exists());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function no_puede_postularse_dos_veces_al_mismo_puesto()
    {
        $cliente = User::factory()->create(['tipo' => 'c', 'cv' => 'cv.pdf']);
        $puesto = Puesto::factory()->create();

        Solicitud::factory()->create([
            'cliente_id' => $cliente->id,
            'puesto_id' => $puesto->id,
        ]);

        $response = $this->actingAs($cliente)
            ->post(route('applications.store', $puesto));

        $response->assertSessionHasErrors([0]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function cliente_puede_ver_sus_solicitudes_enviadas()
    {
        $cliente = User::factory()->create(['tipo' => 'c']);
        $solicitudes = Solicitud::factory(3)->create(['cliente_id' => $cliente->id]);

        $response = $this->actingAs($cliente)
            ->get(route('applications.sent'));

        $response->assertStatus(200);
        foreach ($solicitudes as $solicitud) {
            $response->assertSee($solicitud->puesto->nombre);
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function empresa_puede_ver_solicitudes_recibidas_para_un_puesto()
    {
        $empresa = User::factory()->create(['tipo' => 'e']);
        $puesto = Puesto::factory()->create(['empresa_id' => $empresa->id]);
        $solicitudes = Solicitud::factory(2)->create(['puesto_id' => $puesto->id]);

        $response = $this->actingAs($empresa)
            ->get(route('applications.received', $puesto));

        $response->assertStatus(200);
        foreach ($solicitudes as $solicitud) {
            $response->assertSee($solicitud->cliente->name);
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function empresa_no_puede_ver_solicitudes_de_otras_empresas()
    {
        $empresa1 = User::factory()->create(['tipo' => 'e']);
        $empresa2 = User::factory()->create(['tipo' => 'e']);
        $puesto = Puesto::factory()->create(['empresa_id' => $empresa2->id]);

        $response = $this->actingAs($empresa1)
            ->get(route('applications.received', $puesto));

        $response->assertStatus(403);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function empresa_puede_aceptar_solicitud()
    {
        $empresa = User::factory()->create(['tipo' => 'e']);
        $puesto = Puesto::factory()->create(['empresa_id' => $empresa->id]);
        $solicitud = Solicitud::factory()->create(['puesto_id' => $puesto->id]);

        $response = $this->actingAs($empresa)
            ->patch(route('applications.accept', $solicitud));

        $response->assertRedirect();
        $this->assertDatabaseHas('solicitudes', [
            'id' => $solicitud->id,
            'estado' => 'a',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function empresa_puede_rechazar_solicitud()
    {
        $empresa = User::factory()->create(['tipo' => 'e']);
        $puesto = Puesto::factory()->create(['empresa_id' => $empresa->id]);
        $solicitud = Solicitud::factory()->create(['puesto_id' => $puesto->id]);

        $response = $this->actingAs($empresa)
            ->patch(route('applications.reject', $solicitud));

        $response->assertRedirect();
        $this->assertDatabaseHas('solicitudes', [
            'id' => $solicitud->id,
            'estado' => 'r',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function cliente_puede_eliminar_su_solicitud()
    {
        $cliente = User::factory()->create(['tipo' => 'c']);
        $solicitud = Solicitud::factory()->create(['cliente_id' => $cliente->id]);

        $response = $this->actingAs($cliente)
            ->delete(route('applications.destroy', $solicitud));

        $response->assertRedirect(route('applications.sent'));
        $this->assertDatabaseMissing('solicitudes', [
            'id' => $solicitud->id,
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function cliente_no_puede_eliminar_solicitudes_ajenas()
    {
        $cliente1 = User::factory()->create(['tipo' => 'c']);
        $cliente2 = User::factory()->create(['tipo' => 'c']);
        $solicitud = Solicitud::factory()->create(['cliente_id' => $cliente2->id]);

        $response = $this->actingAs($cliente1)
            ->delete(route('applications.destroy', $solicitud));

        $response->assertStatus(403);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function cliente_puede_ver_su_solicitud()
    {
        $cliente = User::factory()->create(['tipo' => 'c']);
        $solicitud = Solicitud::factory()->create(['cliente_id' => $cliente->id]);

        $response = $this->actingAs($cliente)
            ->get(route('applications.show', $solicitud));

        $response->assertStatus(200);
        $response->assertSee($solicitud->puesto->nombre);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function empresa_puede_ver_solicitud_de_su_puesto()
    {
        $empresa = User::factory()->create(['tipo' => 'e']);
        $puesto = Puesto::factory()->create(['empresa_id' => $empresa->id]);
        $solicitud = Solicitud::factory()->create(['puesto_id' => $puesto->id]);

        $response = $this->actingAs($empresa)
            ->get(route('applications.show', $solicitud));

        $response->assertStatus(200);
        $response->assertSee($solicitud->cliente->name);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function cliente_puede_ver_sus_puestos_aceptados()
    {
        $cliente = User::factory()->create(['tipo' => 'c']);
        $solicitudesAceptadas = Solicitud::factory()->count(3)->create([
            'cliente_id' => $cliente->id,
            'estado' => 'a',
        ]);

        $response = $this->actingAs($cliente)
            ->get(route('myacceptedjobs.index'));

        $response->assertStatus(200);
        foreach ($solicitudesAceptadas as $solicitud) {
            $response->assertSee($solicitud->puesto->nombre);
        }
    }
}
