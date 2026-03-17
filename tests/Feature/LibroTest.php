// tests/Feature/LibroTest.php
<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Libro;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LibroTest extends TestCase
{
    use RefreshDatabase; // Esto reinicia la BD después de cada prueba

    // Helper para crear roles y asignarlos fácilmente
    protected function setUp(): void
    {
        parent::setUp();
        // Ejecutar el seeder de roles antes de cada prueba
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    /** @test */
    public function un_bibliotecario_puede_crear_un_libro()
    {
        // 1. Crear un usuario con rol 'bibliotecario'
        $user = User::factory()->create();
        $user->assignRole('bibliotecario');

        // 2. Datos del nuevo libro
        $libroData = [
            'titulo' => 'El Principito',
            'autor' => 'Antoine de Saint-Exupéry',
            'isbn' => '1234567890',
        ];

        // 3. Hacer la petición POST como ese usuario
        $response = $this->actingAs($user)->postJson('/api/libros', $libroData); // Ajusta la ruta

        // 4. Afirmar (Assert) que la respuesta fue exitosa (201 Created)
        $response->assertStatus(201);
        $this->assertDatabaseHas('libros', ['titulo' => 'El Principito']);
    }

    /** @test */
    public function un_estudiante_NO_puede_crear_un_libro()
    {
        // 1. Crear un usuario con rol 'estudiante'
        $user = User::factory()->create();
        $user->assignRole('estudiante');

        $libroData = [
            'titulo' => 'Cien Años de Soledad',
            'autor' => 'Gabriel García Márquez',
            'isbn' => '0987654321',
        ];

        // 2. Hacer la petición POST
        $response = $this->actingAs($user)->postJson('/api/libros', $libroData);

        // 3. Afirmar que el acceso NO está autorizado (403 Forbidden)
        $response->assertStatus(403);
        $this->assertDatabaseMissing('libros', ['titulo' => 'Cien Años de Soledad']);
    }

    /** @test */
    public function un_bibliotecario_puede_actualizar_un_libro()
    {
        $user = User::factory()->create();
        $user->assignRole('bibliotecario');

        $libro = Libro::factory()->create(['titulo' => 'Título Viejo']);

        $response = $this->actingAs($user)->putJson("/api/libros/{$libro->id}", [
            'titulo' => 'Título Nuevo'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('libros', ['id' => $libro->id, 'titulo' => 'Título Nuevo']);
    }

    // ... Aquí deberías crear TODAS las pruebas para:
    // - Autenticación (login, logout, perfil)
    // - CRUD de libros (listar, ver detalle, crear, actualizar, eliminar) probando con diferentes roles.
    // - Préstamos (prestar, devolver, historial) probando con diferentes roles.
}